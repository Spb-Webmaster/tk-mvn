<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('training_categories', 'slug')) {
            Schema::table('training_categories', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('title');
            });
        }

        foreach (DB::table('training_categories')->get() as $row) {
            $slug = Str::slug($row->title);
            $original = $slug;
            $i = 2;

            while (DB::table('training_categories')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                $slug = $original . '-' . $i++;
            }

            DB::table('training_categories')->where('id', $row->id)->update(['slug' => $slug]);
        }

        Schema::table('training_categories', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('training_categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
