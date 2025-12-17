<?php

use Illuminate\Database\QueryException ;

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
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary Key
            $table->string('invoice_number')->unique(); // Unique Invoice Number
            // $table->unsignedBigInteger('pr_number_id'); // Project Reference
            // $table->foreign('pr_number_id')->references('id')->on('projects')->onDelete('cascade'); // Foreign Key for Projects 
            $table->foreignId('pr_number')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');                  
            $table->decimal('value', 10, 2); // Invoice Value
            $table->string('invoice_copy_path'); // Invoice Copy Path
            $table->string('status')->nullable(); // Invoice Status
            $table->decimal('pr_invoices_total_value', 10, 2)->nullable(); // PR Invoices Total Value
            $table->timestamps(); // Created & Updated Timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};














// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     /**
//      * Run the migrations.
//      */
//     public function up(): void
//     {
//         Schema::create('invoices', function (Blueprint $table) {
//             $table->bigIncrements('id');
//             $table->string('invoice_number')->unique(); // Invoice number
//             $table->decimal('value', 10, 2); // Value
//             $table->unsignedBigInteger('pr_number_id');
//             $table->foreign('pr_number_id')->references('id')->on('projects');
//                         $table->string('invoice_copy_path'); // Path to the Invoice copy
//             $table->string('status'); // Path to the Invoice copy
//             $table->decimal('pr_invoices_total_value', 10, 2)->nullable(); // PR Invoices total Value
//             $table->timestamps(); // Created and updated timestamps

        
//         });
//     }

//     /**
//      * Reverse the migrations.
//      */
//     public function down(): void
//     {
//         Schema::dropIfExists('invoices');
//     }
// };
