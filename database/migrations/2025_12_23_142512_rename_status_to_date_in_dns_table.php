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
        // First, add a new date column
        Schema::table('dns', function (Blueprint $table) {
            $table->date('date')->nullable()->after('dn_copy');
        });

        // Drop the old status column
        Schema::table('dns', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the status column
        Schema::table('dns', function (Blueprint $table) {
            $table->string('status', 500)->nullable()->after('dn_copy');
        });

        // Drop the date column
        Schema::table('dns', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
};
