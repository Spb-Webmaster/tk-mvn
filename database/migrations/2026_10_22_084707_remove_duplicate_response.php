<?php

use Database\Seeders\RemoveDuplicateResponseSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Разовое удаление дубля отзыва «Положительный заряд!».
 *
 * Деплой выполняет только `artisan migrate`, поэтому сидер запускается отсюда —
 * чистка пройдёт при развёртывании один раз.
 */
return new class extends Migration
{
    public function up(): void
    {
        (new RemoveDuplicateResponseSeeder())->run();
    }

    public function down(): void
    {
        // Удалённый контент не восстанавливаем.
    }
};
