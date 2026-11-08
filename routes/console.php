<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('queue:work --stop-when-empty')
    ->everyMinute()
    ->withoutOverlapping();

// Копия базы и файлов на Яндекс Диск. Ночь субботы, когда на сайте никого нет.
// withoutOverlapping(720) — если прошлый запуск завис, новый не стартует 12 часов.
Schedule::command('backup:yadisk')
    ->weeklyOn(6, '02:30')
    ->runInBackground()
    ->withoutOverlapping(720);

Schedule::command('sitemap:cron')->dailyAt('01:10');

Schedule::command('training:clear-dates')->dailyAt('00:10');
//Schedule::command('training:clear-dates')->everyMinute();
