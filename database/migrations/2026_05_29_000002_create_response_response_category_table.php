<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('response_response_category');
        Schema::create('response_response_category', function (Blueprint $table) {
            $table->foreignId('response_id')->constrained('responses')->cascadeOnDelete();
            $table->foreignId('response_category_id')->constrained('response_categories')->cascadeOnDelete();
            $table->primary(['response_id', 'response_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('response_response_category');
    }
};
