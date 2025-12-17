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
        Schema::create('dns', function (Blueprint $table) {
            $table->id();
            $table->string('dn_number')->unique(); // تغيير من text إلى string مع unique constraint
            $table->foreignId('pr_number')->nullable()->constrained('projects');
            $table->string('dn_copy')->nullable(); // ملف PDF أو صورة
            $table->text('status')->nullable(); // حقل نصي
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dns');
    }
};
