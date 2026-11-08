<?php

declare(strict_types=1);

namespace App\Console\Commands;

use GuzzleHttp\Psr7\Utils;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use RuntimeException;

/**
 * Копия сайта на Яндекс Диск: база данных + файлы.
 *
 *   php artisan backup:yadisk
 *
 * Куда: папка OAuth-приложения на Диске («Приложения/<имя приложения>»), подпапка по домену:
 *   Приложения/<имя приложения>/<домен>/<домен>-db.sql.gz
 *   Приложения/<имя приложения>/<домен>/<домен>-files.tar.gz
 * Домен — из APP_URL; можно задать BACKUP_YADISK_SITE в .env.
 * Копия одна, каждый запуск её перезаписывает. Старая заменяется только после
 * того, как новая загрузилась целиком (грузим как .new, потом переносим).
 *
 * Доступ к Диску — OAuth-приложение (oauth.yandex.ru) с единственным
 * правом cloud_api:disk.app_folder: ключ видит только свою папку, остальной Диск — нет.
 * Ключ и данные приложения — storage/app/backup/yadisk-token.json (вне git и вне public).
 * Срок ключа год; команда продлевает его сама, когда до конца меньше 30 дней.
 *
 * В архив не входит то, что восстанавливается само: vendor, node_modules, .git, логи,
 * кэши Laravel, public/build (пересобирается при деплое) и сама папка storage/app/backup.
 *
 * Видео занимает около 2,2 ГБ из 2,5 ГБ всех файлов и меняется редко, поэтому его можно
 * исключить: BACKUP_YADISK_SKIP_VIDEO=true в .env. Тогда архив — около 290 МБ, но видео
 * в копию не попадает и должно храниться отдельно.
 *
 * Шаблон из скилла yadisk-backup (~/.claude/skills/yadisk-backup). Нужен Laravel 10+ (фасад Process).
 * Запускается планировщиком сайта от пользователя сайта, в фоне.
 * Дамп и архив делают mysqldump / tar отдельными процессами с низким приоритетом —
 * PHP не держит файлы в памяти, загрузка идёт потоком.
 */
class BackupYandexDisk extends Command
{
    protected $signature = 'backup:yadisk';

    protected $description = 'Копия базы и файлов сайта на Яндекс Диск (папка приложения)';

    private const API = 'https://cloud-api.yandex.net/v1/disk';

    private const TOKEN_FILE = 'app/backup/yadisk-token.json';

    /** Продлеваем ключ, если до конца срока меньше стольких дней */
    private const REFRESH_DAYS = 30;

    /** Пути относительно корня проекта, которые не нужны в копии */
    private const EXCLUDE = [
        'vendor', 'node_modules', '.git',
        'storage/logs', 'storage/framework/cache', 'storage/framework/sessions',
        'storage/framework/views', 'storage/app/backup',
        'public/build',
    ];

    /** Маски видеофайлов — исключаются при BACKUP_YADISK_SKIP_VIDEO=true */
    private const VIDEO_PATTERNS = ['*.mp4', '*.mov', '*.avi', '*.webm', '*.mkv', '*.m4v'];

    private string $token;

    public function handle(): int
    {
        $site = env('BACKUP_YADISK_SITE') ?: (parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'site');
        $tmp = storage_path('app/backup/tmp');
        $started = microtime(true);
        $report = [];

        @mkdir($tmp, 0700, true);
        $db = "$tmp/$site-db.sql.gz";
        $files = "$tmp/$site-files.tar.gz";

        try {
            $this->token = $this->accessToken();

            $this->dumpDatabase($db);
            $report[] = 'База: ' . $this->size($db);

            $this->archiveFiles($files);
            $report[] = 'Файлы: ' . $this->size($files) . ($this->skipVideo() ? ' (без видео)' : '');

            $this->ensureFolder("app:/$site");
            foreach ([$db, $files] as $path) {
                $this->upload($path, "app:/$site/" . basename($path));
            }

            $report[] = sprintf('Готово за %d с — Яндекс Диск: папка приложения/%s', microtime(true) - $started, $site);
            $this->finish('backup:yadisk — ' . $site, $report);

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $report[] = 'ОШИБКА: ' . $e->getMessage();
            $this->finish('backup:yadisk — ' . $site . ' — ОШИБКА', $report, true);

            return self::FAILURE;
        } finally {
            @unlink($db);
            @unlink($files);
        }
    }

    private function skipVideo(): bool
    {
        return filter_var(env('BACKUP_YADISK_SKIP_VIDEO', false), FILTER_VALIDATE_BOOLEAN);
    }

    /* ---------- ключ доступа ---------- */

    private function accessToken(): string
    {
        $file = storage_path(self::TOKEN_FILE);
        if (! is_file($file)) {
            throw new RuntimeException('нет файла ключа ' . $file);
        }

        $data = json_decode((string) file_get_contents($file), true) ?: [];
        if (empty($data['access_token'])) {
            throw new RuntimeException('в файле ключа нет access_token');
        }

        $expires = isset($data['expiry']) ? strtotime($data['expiry']) : 0;
        if ($expires && $expires - time() < self::REFRESH_DAYS * 86400) {
            $data = $this->refreshToken($data);
            file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            @chmod($file, 0600);
            $this->line('ключ продлён до ' . $data['expiry']);
        }

        return $data['access_token'];
    }

    private function refreshToken(array $data): array
    {
        $response = Http::asForm()->timeout(30)->post('https://oauth.yandex.ru/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $data['refresh_token'] ?? '',
            'client_id' => $data['client_id'] ?? '',
            'client_secret' => $data['client_secret'] ?? '',
        ]);

        if (! $response->successful() || ! $response->json('access_token')) {
            throw new RuntimeException('не удалось продлить ключ: ' . $response->body());
        }

        return array_merge($data, [
            'access_token' => $response->json('access_token'),
            'refresh_token' => $response->json('refresh_token') ?: ($data['refresh_token'] ?? ''),
            'expiry' => date(DATE_ATOM, time() + (int) $response->json('expires_in')),
        ]);
    }

    /* ---------- дамп и архив ---------- */

    private function dumpDatabase(string $target): void
    {
        $c = config('database.connections.' . config('database.default'));

        // пароль — во временном файле с правами 600, не в командной строке (её видно в ps)
        $cnf = tempnam(dirname($target), 'my');
        chmod($cnf, 0600);
        file_put_contents($cnf, sprintf("[client]\nuser=%s\npassword=%s\nhost=%s\nport=%s\n",
            $c['username'], $c['password'], $c['host'] ?: '127.0.0.1', $c['port'] ?: 3306));

        try {
            $this->shell(sprintf(
                'nice -n 19 mysqldump --defaults-extra-file=%s --single-transaction --quick --routines --triggers '
                . '--default-character-set=utf8mb4 %s | nice -n 19 gzip -6 > %s && gzip -t %s',
                escapeshellarg($cnf), escapeshellarg($c['database']), escapeshellarg($target), escapeshellarg($target)
            ));
        } finally {
            @unlink($cnf);
        }
    }

    private function archiveFiles(string $target): void
    {
        $root = base_path();
        $name = basename($root);

        $exclude = array_map(fn ($p) => '--exclude=' . escapeshellarg("$name/$p"), self::EXCLUDE);

        // Маски без пути tar применяет к имени файла на любом уровне вложенности
        if ($this->skipVideo()) {
            foreach (self::VIDEO_PATTERNS as $pattern) {
                $exclude[] = '--exclude=' . escapeshellarg($pattern);
            }
        }

        // код 1 у tar — «файл изменился при чтении»: для живого сайта норма
        $this->shell(sprintf(
            'nice -n 19 tar -czf %s -C %s %s --warning=no-file-changed %s; rc=$?; [ $rc -le 1 ]',
            escapeshellarg($target), escapeshellarg(dirname($root)), implode(' ', $exclude), escapeshellarg($name)
        ));
    }

    private function shell(string $command): void
    {
        $result = Process::timeout(3600)->run(['bash', '-o', 'pipefail', '-c', $command]);
        if (! $result->successful()) {
            throw new RuntimeException(trim($result->errorOutput()) ?: 'команда завершилась с кодом ' . $result->exitCode());
        }
    }

    /* ---------- Яндекс Диск ---------- */

    private function disk()
    {
        return Http::withHeaders(['Authorization' => 'OAuth ' . $this->token])->acceptJson()->timeout(60);
    }

    private function ensureFolder(string $path): void
    {
        $response = $this->disk()->send('PUT', self::API . '/resources?path=' . rawurlencode($path));
        // 201 — создана, 409 — уже есть
        if (! in_array($response->status(), [201, 409], true)) {
            throw new RuntimeException("папка $path: HTTP {$response->status()} " . $response->body());
        }
    }

    /** Файл → .new на Диске, затем перенос поверх прежней копии */
    private function upload(string $local, string $remote): void
    {
        $tmp = $remote . '.new';

        $href = $this->disk()
            ->get(self::API . '/resources/upload', ['path' => $tmp, 'overwrite' => 'true'])
            ->throw()
            ->json('href');

        $stream = Utils::streamFor(Utils::tryFopen($local, 'r'));
        Http::withBody($stream, 'application/octet-stream')
            ->timeout(3600)
            ->retry(3, 10000)
            ->put($href)
            ->throw();

        $move = $this->disk()->send('POST', self::API . '/resources/move?' . http_build_query([
            'from' => $tmp,
            'path' => $remote,
            'overwrite' => 'true',
        ]));

        // 201/202 — перенесено или переносится; 202 — асинхронно, ждём завершения
        if ($move->status() === 202 && ($operation = $move->json('href'))) {
            for ($i = 0; $i < 60; $i++) {
                $status = $this->disk()->get($operation)->json('status');
                if ($status === 'success') {
                    break;
                }
                if ($status === 'failed') {
                    throw new RuntimeException("перенос $tmp → $remote не удался");
                }
                sleep(2);
            }
        } elseif (! $move->successful()) {
            $move->throw();
        }

        $this->line(sprintf('загружено: %s (%s)', $remote, $this->size($local)));
    }

    /* ---------- вывод ---------- */

    private function size(string $path): string
    {
        $bytes = (int) @filesize($path);

        return $bytes >= 1048576 ? round($bytes / 1048576) . ' МБ' : round($bytes / 1024) . ' КБ';
    }

    /** В лог всегда; письмо админу — только при ошибке (раз в неделю «всё хорошо» не нужно) */
    private function finish(string $title, array $report, bool $error = false): void
    {
        foreach ($report as $line) {
            $error ? $this->error($line) : $this->info($line);
        }
        Log::{$error ? 'error' : 'info'}($title . ': ' . implode('; ', $report));
    }
}
