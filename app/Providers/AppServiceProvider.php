<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Делаем один запрос к БД и разделяем массив константных настроек
        // между всеми Blade-шаблонами. Переменная $constants становится
        // доступна в любом шаблоне автоматически — без передачи из контроллера.
        // Благодаря кэшу в Setting::getGroup повторные обращения из контроллеров
        // (например, TrainerController берёт contact_phone) не создают новый запрос.
        View::share('constants', Setting::getGroup('constants')->data ?? []);

        Password::defaults(function () {
            return Password::min(5)
                /*      ->letters()
                      ->numbers()
                      ->symbols()
                      ->mixedCase()
                      ->uncompromised()*/;
        });
    }
}
