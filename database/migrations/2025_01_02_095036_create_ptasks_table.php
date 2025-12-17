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
        Schema::create('ptasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pr_number')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');
            //$table->timestamp('task_date')->default(now());
            $table->timestamp('task_date');
            $table->string('details')->nullable();
            $table->string("assigned")->nullable();
            $table->date("expected_com_date")->nullable();
            $table->enum("status",['completed','pending','progress','hold']);
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ptasks');
    }
};
