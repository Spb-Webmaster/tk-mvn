<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('photo_photo_category');
        Schema::create('photo_photo_category', function (Blueprint $table) {
            $table->foreignId('photo_id')->constrained('photos')->cascadeOnDelete();
            $table->foreignId('photo_category_id')->constrained('photo_categories')->cascadeOnDelete();
            $table->primary(['photo_id', 'photo_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photo_photo_category');
    }
};
