<?php

use Database\Seeders\CorporatePageSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Начальное наполнение страницы «Компаниям».
 *
 * Деплой выполняет только `artisan migrate`, поэтому сидер запускается отсюда —
 * контент появится при развёртывании один раз и не затрёт правки в админке.
 */
return new class extends Migration
{
    public function up(): void
    {
        (new CorporatePageSeeder())->run();
    }

    public function down(): void
    {
        // Контент страницы не удаляем.
    }
};
