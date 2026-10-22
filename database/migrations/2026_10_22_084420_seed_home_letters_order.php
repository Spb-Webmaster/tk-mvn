<?php

use Database\Seeders\ResponseHomeLettersSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Разовая расстановка писем для блока на главной.
 *
 * Деплой выполняет только `artisan migrate`, поэтому сидер запускается отсюда:
 * так порядок проставится при развёртывании один раз и не будет затирать
 * правки, сделанные потом в админке.
 */
return new class extends Migration
{
    public function up(): void
    {
        (new ResponseHomeLettersSeeder())->run();
    }

    public function down(): void
    {
        // Данные контента — откат не требуется.
    }
};
