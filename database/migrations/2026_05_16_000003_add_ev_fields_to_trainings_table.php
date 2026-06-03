<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->date('ev_date_from')->nullable()->after('sorting');
            $table->date('ev_date_to')->nullable()->after('ev_date_from');
            $table->string('ev_time')->nullable()->after('ev_date_to');
            $table->string('ev_location')->nullable()->default('Санкт-Петербург')->after('ev_time');
            $table->integer('ev_price_legal')->nullable()->after('ev_location');
            $table->integer('ev_price_individual')->nullable()->after('ev_price_legal');
            $table->json('ev_modules')->nullable()->after('ev_price_individual');
            $table->json('ev_goals')->nullable()->after('ev_modules');
            $table->json('ev_tasks')->nullable()->after('ev_goals');
        });
    }

    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn([
                'ev_date_from',
                'ev_date_to',
                'ev_time',
                'ev_location',
                'ev_price_legal',
                'ev_price_individual',
                'ev_modules',
                'ev_goals',
                'ev_tasks',
            ]);
        });
    }
};
