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
        Schema::create('custs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('abb')->nullable();
            $table->enum('tybe', ['GOV', 'PRIVATE'])->nullable();
            $table->string('logo')->nullable();
            $table->string('customercontactname')->nullable();
            $table->string('customercontactposition')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custs');
    }
};
