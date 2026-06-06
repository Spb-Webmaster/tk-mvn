<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('queue:work --stop-when-empty')
    ->everyMinute()
    ->withoutOverlapping();

Schedule::command('sitemap:cron')->everyMinute();

Schedule::command('training:clear-dates')->dailyAt('00:10');
//Schedule::command('training:clear-dates')->everyMinute();
