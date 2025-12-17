

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
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pr_number')->unique();
            $table->string('name');

            // التقنيات المستخدمة (Technologies)
            $table->string('technologies')->nullable();

            // مرجع البائع (Vendor Reference)
            $table->unsignedBigInteger('vendors_id')->nullable();
            $table->foreign('vendors_id')
                  ->references('id')
                  ->on('vendors')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('ds_id')->nullable();
            $table->foreign('ds_id')
                  ->references('id')
                  ->on('ds')
                  ->onDelete('cascade');


            $table->unsignedBigInteger('cust_id')->nullable();
            $table->foreign('cust_id')
                  ->references('id')
                  ->on('custs')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('aams_id')->nullable();
            $table->foreign('aams_id')
                  ->references('id')
                  ->on('aams')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('ppms_id')->nullable();
            $table->foreign('ppms_id')
                  ->references('id')
                  ->on('ppms')
                  ->onDelete('cascade');

            $table->decimal('value', 15, 2)->nullable();
            $table->string('customer_po')->nullable(); // طلب الشراء الخاص بالعميل
            $table->string('customer_contact_details')->nullable(); // بيانات اتصال العميل
            $table->string('po_attachment')->nullable(); // مرفق طلب الشراء
            $table->string('epo_attachment')->nullable(); // مرفق الطلب الإلكتروني
            $table->date('customer_po_date')->nullable(); // تاريخ طلب الشراء الخاص بالعميل
            $table->string('customer_po_duration', 50)->nullable(); // مدة طلب الشراء

            $table->date('customer_po_deadline')->nullable(); // الموعد النهائي لطلب الشراء
            $table->text('description')->nullable();
            $table->engine = 'InnoDB';
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
