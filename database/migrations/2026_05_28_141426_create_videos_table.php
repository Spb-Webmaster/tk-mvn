<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('template')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('short_desc')->nullable();
            $table->string('img')->nullable();
            $table->longText('desc')->nullable();
            $table->string('img2')->nullable();
            $table->longText('desc2')->nullable();
            $table->text('html')->nullable();
            $table->text('html2')->nullable();
            $table->boolean('published')->default(false);
            $table->json('params')->nullable();
            $table->json('video')->nullable();
            $table->json('gallery')->nullable();
            $table->json('files')->nullable();
            $table->string('metatitle')->nullable();
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->text('script')->nullable();
            $table->integer('sorting')->default(0);
            $table->json('faq')->nullable();
            $table->text('custom_field')->nullable();
            $table->text('custom_field2')->nullable();
            $table->text('custom_field3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
