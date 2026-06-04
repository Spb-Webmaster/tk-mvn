# Деплой Laravel на виртуальный хостинг Beget

## Особенности Beget

- SSH-пользователь заперт в chroot: домашняя директория = `public_html/`
- Document root изменить нельзя — всегда `public_html/`
- Composer предустановлен версии 1.x — **не подходит** для Laravel 10+
- PHP доступен через `php8.3`, `php8.2` и т.д.
- `sed -i` не работает на файлах созданных другим процессом — только `cat >>` или полная перезапись

---

## Структура проекта на сервере

```
public_html/          ← chroot-корень, сюда деплоятся все файлы Laravel
├── app/
├── bootstrap/
├── config/
├── public/           ← реальная веб-директория
├── storage/
├── .htaccess         ← маршрутизирует запросы из public_html/ в public/
└── ...
```

Так как document root изменить нельзя, в корне проекта лежит `.htaccess`, который:
- Блокирует прямой доступ к `app/`, `config/`, `.env` и т.д.
- Направляет статику из `public/`
- Всё остальное отдаёт в `public/index.php`

---

## Подготовка: одноразовые шаги

### 1. Установить Composer 2 на сервере

```bash
ssh axeld1975_test@axeld1975.beget.tech
wget -q https://getcomposer.org/download/latest-2.x/composer.phar -O ~/composer2.phar
chmod +x ~/composer2.phar
php8.3 ~/composer2.phar --version
```

### 2. Сгенерировать SSH-ключ для деплоя

```bash
ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/beget_deploy -N ""
```

### 3. Добавить публичный ключ на Beget

Через PuTTY plink (Windows):
```powershell
$pubkey = Get-Content "~/.ssh/beget_deploy.pub"
"y" | & "C:\Program Files\PuTTY\plink.exe" -ssh -pw "ПАРОЛЬ" USER@HOST `
  "mkdir -p ~/.ssh && chmod 711 ~/.ssh && echo '$pubkey' >> ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys"
```

### 4. Добавить секреты в GitHub

`Settings → Secrets and variables → Actions`:

| Secret | Значение |
|---|---|
| `SSH_HOST` | `axeld1975.beget.tech` |
| `SSH_USER` | `axeld1975_test` |
| `SSH_PRIVATE_KEY` | содержимое `~/.ssh/beget_deploy` |
| `ENV_PRODUCTION` | содержимое `.env` для продакшена |

### 5. Настроить cron для очередей

В панели Beget → Cron:
```
* * * * * /usr/local/php8.3/bin/php /home/a/USERNAME/DOMAIN/public_html/artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

---

## .htaccess в корне проекта

Файл `.htaccess` (в корне Laravel, не в `public/`):

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Блокируем доступ к служебным файлам
    RewriteRule ^\.env - [F,L]
    RewriteRule ^artisan - [F,L]
    RewriteRule ^composer\.(json|lock)$ - [F,L]
    RewriteRule ^package(-lock)?\.json$ - [F,L]
    RewriteRule ^phpunit\.xml$ - [F,L]
    RewriteRule ^(app|bootstrap|config|database|lang|resources|routes|src|storage|vendor)(/|$) - [F,L]

    # Статика из public/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{DOCUMENT_ROOT}/public%{REQUEST_URI} -f
    RewriteRule ^(.+)$ public/$1 [L,QSA]

    # Всё остальное → Laravel
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ public/index.php [L,QSA]
</IfModule>
```

> Важно: финальное правило не содержит `!-d`, иначе корневой URL `/` возвращает 403.

---

## GitHub Actions: deploy.yml

```yaml
name: Deploy

on:
  push:
    branches: [main]
  workflow_dispatch:

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout
        uses: actions/checkout@v4

      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: '20'
          cache: 'npm'

      - name: Build frontend assets
        run: |
          npm ci
          npm run build

      - name: Setup SSH key
        run: |
          mkdir -p ~/.ssh
          echo "${{ secrets.SSH_PRIVATE_KEY }}" > ~/.ssh/deploy_key
          chmod 600 ~/.ssh/deploy_key
          ssh-keyscan -H ${{ secrets.SSH_HOST }} >> ~/.ssh/known_hosts

      - name: Deploy files via rsync
        run: |
          rsync -rltz --delete \
            --no-perms --no-owner --no-group --omit-dir-times \
            --exclude='.git' \
            --exclude='.ssh' \
            --exclude='.cache' \
            --exclude='composer2.phar' \
            --exclude='node_modules' \
            --exclude='.env' \
            --exclude='/vendor' \
            --exclude='storage/app/public' \
            --exclude='storage/logs' \
            --exclude='storage/framework/sessions' \
            --exclude='storage/framework/cache' \
            --exclude='storage/framework/views' \
            --exclude='public/storage' \
            -e "ssh -i ~/.ssh/deploy_key -o StrictHostKeyChecking=no" \
            ./ ${{ secrets.SSH_USER }}@${{ secrets.SSH_HOST }}:~/

      - name: Post-deploy tasks
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: ${{ secrets.SSH_HOST }}
          username: ${{ secrets.SSH_USER }}
          key: ${{ secrets.SSH_PRIVATE_KEY }}
          script: |
            set -e
            cd ~

            cat > .env << '__ENV_EOF__'
            ${{ secrets.ENV_PRODUCTION }}
            __ENV_EOF__

            mkdir -p storage/framework/{cache/data,sessions,testing,views} storage/app/public storage/logs
            chmod -R 775 storage bootstrap/cache

            php8.3 ~/composer2.phar install --no-dev --optimize-autoloader --no-interaction --no-scripts

            php8.3 artisan package:discover --ansi
            php8.3 artisan migrate --force
            php8.3 artisan config:cache
            php8.3 artisan route:cache
            php8.3 artisan view:cache
            php8.3 artisan storage:link --force
```

---

## Ключевые флаги rsync для Beget

| Флаг | Причина |
|---|---|
| `--no-perms` | Нельзя менять права в chroot |
| `--no-owner` | Нельзя менять владельца |
| `--no-group` | Нельзя менять группу |
| `--omit-dir-times` | Нельзя устанавливать mtime на корневую директорию |

Без этих флагов rsync завершается с **exit code 23**.

---

## Медиафайлы (storage)

Директории `storage/app/public`, `storage/logs`, `storage/framework/*` и `public/storage` исключены из rsync чтобы не затирать данные между деплоями. Без `--exclude='storage/app/public'` и `--exclude='public/storage'` rsync с флагом `--delete` удалит загруженные пользователем медиафайлы и симлинк, созданный `artisan storage:link`. Их нужно создать при первом деплое (делается автоматически через `mkdir -p`).

Медиафайлы загружённые через админку хранятся в `storage/app/public/` — они **не деплоятся через git**. При первичной настройке загружать по SFTP:

```
Хост:     axeld1975.beget.tech
Порт:     22 (SFTP)
Логин:    axeld1975_test
Папка:    /storage/app/public/
```

---

## Типовые ошибки

| Ошибка | Причина | Решение |
|---|---|---|
| `rsync exit code 23` | Права на директорию | Добавить `--no-perms --no-owner --no-group --omit-dir-times` |
| `403 Forbidden` на `/` | `!-d` в `.htaccess` | Убрать `!-d` из финального RewriteRule |
| `403 Forbidden` на `/storage/...` | `storage/` заблокирован правилами `.htaccess` | Добавить правило ДО блоков (см. ниже) |
| `404` на `/storage/...` после снятия 403 | Файлы загружены не туда или не через SFTP | Проверить путь и протокол (SFTP, порт 22, логин `axeld1975_test`) |
| `Please provide a valid cache path` | Нет `storage/framework/views` | `mkdir -p storage/framework/{cache/data,sessions,testing,views}` |
| `package:discover exit 1` | Падает из-за отсутствующих директорий | Сначала `mkdir -p`, потом `artisan` |
| `composer` устарел | Версия 1.x на Beget | Установить `~/composer2.phar` вручную |
| Белый экран в `/admin/` | `vendor/` заблокирован в `.htaccess`, JS/CSS MoonShine не грузятся | Добавить `RewriteRule ^vendor/(.*)$ public/vendor/$1` ДО блоков |
| `View [default] not found` (500 на страницах с пагинацией) | `--exclude='vendor'` в rsync исключает и `resources/views/vendor/` | Использовать `--exclude='/vendor'` (с ведущим слэшем) |

---

## Исправление: доступ к /storage/ (медиафайлы)

По умолчанию `.htaccess` блокирует весь `storage/` чтобы защитить логи и кэш. Но URL вида `/storage/images/...` должен вести на симлинк `public/storage/` → `storage/app/public/`.

Правило нужно добавить **до** блокирующих строк:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    Options +FollowSymLinks

    # Публичные медиафайлы через storage:link (public/storage → storage/app/public)
    RewriteRule ^storage/(.*)$ public/storage/$1 [L,QSA]

    # Блокируем прямой доступ к служебным файлам и директориям
    RewriteRule ^\.env - [F,L]
    ...
```

Почему это безопасно: `public/storage/` — симлинк только на `storage/app/public/`. Запросы к `storage/logs/`, `storage/framework/` и т.д. через этот редирект вернут 404, так как этих папок внутри `storage/app/public/` нет.

---

## Исправление: белый экран в /admin/ (MoonShine)

MoonShine загружает ассеты по URL вида `/vendor/moonshine/assets/app.js`. Блокирующее правило в `.htaccess`:

```apache
RewriteRule ^(app|bootstrap|config|...|vendor)(/|$) - [F,L]
```

срабатывает **до** редиректа на `public/` и возвращает **403** на любой `/vendor/*` запрос — из-за чего Alpine.js и CSS не загружаются, страница выглядит пустой.

Решение: добавить явный редирект `vendor/ → public/vendor/` **до** блокирующих правил:

```apache
# Публичные ассеты из public/vendor/ (MoonShine, TinyMCE и др.)
RewriteRule ^vendor/(.*)$ public/vendor/$1 [L,QSA]
```

Почему это безопасно: PHP-пакеты находятся в корневом `vendor/`, а `public/vendor/` содержит только опубликованные статические ассеты. Запросы вида `/vendor/laravel/framework/...` вернут 404, так как таких файлов в `public/vendor/` нет.

---

### Загрузка медиафайлов через SFTP

Обязательно использовать **SFTP** (не FTP), иначе файлы попадут в другую директорию другого пользователя.

| Параметр | Значение |
|---|---|
| Протокол | SFTP (порт 22) |
| Хост | `axeld1975.beget.tech` |
| Логин | `axeld1975_test` |
| Корень в клиенте | `/` = `public_html/` |
| Путь для медиа | `/storage/app/public/` |
