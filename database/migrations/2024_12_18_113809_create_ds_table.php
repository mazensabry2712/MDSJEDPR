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
        Schema::create('ds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('dsname')->unique();
            $table->string('ds_contact', 2000);
            $table->timestamps();

            // Add index for better performance
            $table->index('dsname');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ds');
    }
};
