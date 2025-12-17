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
        Schema::table('pepos', function (Blueprint $table) {
            // Drop unique constraint from category column
            $table->dropUnique('pepos_category_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pepos', function (Blueprint $table) {
            // Re-add unique constraint if rolling back
            $table->unique('category', 'pepos_category_unique');
        });
    }
};
