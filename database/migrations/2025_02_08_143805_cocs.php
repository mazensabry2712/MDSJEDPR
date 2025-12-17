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
        Schema::create('cocs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pr_number')->nullable()->constrained('projects');
            $table->string('coc_copy')->nullable(); // ملف PDF أو صورة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cocs');
    }
};