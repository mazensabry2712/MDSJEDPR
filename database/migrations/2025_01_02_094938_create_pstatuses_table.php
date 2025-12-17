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
        Schema::create('pstatuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pr_number')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');
            // $table->timestamp('date_time')->default(now());
            $table->date('date_time')->nullable();
          
            $table->foreignId('pm_name')->references('id')->on('ppms')->onDelete('cascade')->onUpdate('cascade');
             $table->string('status')->nullable();
             $table->decimal('actual_completion', 5, 2)->nullable();
             $table->date('expected_completion')->nullable();
             $table->text('date_pending_cost_orders')->nullable();
             $table->text('notes')->nullable();
             
             

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pstatuses');
    }
};
