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
        Schema::create('pepos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pr_number')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');
            $table->string('category')->unique()->nullable();
            $table->decimal('planned_cost', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->decimal('margin', 5, 2)->storedAs('(selling_price - planned_cost) / selling_price ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pepos');
    }
};
