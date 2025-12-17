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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('pr_number')->nullable();
            $table->string('project_name')->nullable();
            $table->string('project_manager')->nullable();
            $table->text('technologies')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_po')->nullable();
            $table->decimal('value', 15, 2)->nullable();
            $table->decimal('invoice_total', 15, 2)->nullable();
            $table->date('customer_po_deadline')->nullable();
            $table->decimal('actual_completion_percentage', 5, 2)->nullable();
            $table->string('vendors')->nullable();
            $table->string('suppliers')->nullable();
            $table->string('am')->nullable();
            $table->timestamps();

            // Add indexes for filtering
            $table->index('pr_number');
            $table->index('project_name');
            $table->index('customer_name');
            $table->index('customer_po_deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
