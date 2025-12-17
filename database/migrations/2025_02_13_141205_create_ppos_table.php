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
        Schema::create('ppos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pr_number')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('category')->references('id')->on('pepos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('dsname')->references('id')->on('ds')->onDelete('cascade')->onUpdate('cascade');

 // من EPO
            $table->string('po_number');
            $table->decimal('value', 10, 2)->nullable();
            $table->date('date')->nullable();
            $table->string('status')->nullable();
            $table->string('updates')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppos');
    }
};
