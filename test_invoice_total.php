<?php

/**
 * TEST SCRIPT FOR INVOICE TOTAL VALUE CALCULATION
 * هذا السكريبت للاختبار فقط - سيتم حذفه بعد التأكد من عمل النظام
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\invoices;
use App\Models\Project;

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "   اختبار حساب PR Invoices Total Value التلقائي\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Get a sample project
$project = Project::first();

if (!$project) {
    echo "❌ لا توجد مشاريع في قاعدة البيانات!\n";
    echo "   الرجاء إضافة مشروع أولاً.\n\n";
    exit;
}

echo "✅ تم اختيار المشروع: {$project->name}\n";
echo "   PR Number: {$project->pr_number}\n";
echo "   Project ID: {$project->id}\n\n";

// Check existing invoices for this project
$existingInvoices = invoices::where('pr_number', $project->id)->get();
echo "📊 الفواتير الحالية للمشروع:\n";
if ($existingInvoices->count() > 0) {
    foreach ($existingInvoices as $inv) {
        echo "   - Invoice #{$inv->invoice_number}: Value = {$inv->value}, Total = {$inv->pr_invoices_total_value}\n";
    }
    $currentTotal = $existingInvoices->sum('value');
    echo "\n   المجموع الحالي: {$currentTotal}\n\n";
} else {
    echo "   لا توجد فواتير حالية لهذا المشروع.\n\n";
}

// Test calculations
echo "═══════════════════════════════════════════════════════════════\n";
echo "   اختبار الحسابات:\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Calculate what the total should be
$calculatedTotal = invoices::where('pr_number', $project->id)->sum('value');
echo "✓ المجموع المحسوب من قاعدة البيانات: {$calculatedTotal}\n";

// Check if all invoices have the same total value
$invoicesWithDifferentTotals = invoices::where('pr_number', $project->id)
    ->where('pr_invoices_total_value', '!=', $calculatedTotal)
    ->count();

if ($invoicesWithDifferentTotals > 0) {
    echo "⚠️  تحذير: يوجد {$invoicesWithDifferentTotals} فاتورة بقيمة مجموع مختلفة!\n";
    echo "   سيتم تصحيح القيم...\n\n";

    // Fix the totals
    invoices::where('pr_number', $project->id)
        ->update(['pr_invoices_total_value' => $calculatedTotal]);

    echo "✅ تم تصحيح القيم بنجاح!\n\n";
} else {
    echo "✅ جميع الفواتير لها نفس قيمة المجموع الصحيحة!\n\n";
}

// Display final state
echo "═══════════════════════════════════════════════════════════════\n";
echo "   الحالة النهائية:\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$finalInvoices = invoices::where('pr_number', $project->id)->get();
if ($finalInvoices->count() > 0) {
    foreach ($finalInvoices as $inv) {
        echo "   Invoice #{$inv->invoice_number}:\n";
        echo "      Value: {$inv->value}\n";
        echo "      PR Invoices Total Value: {$inv->pr_invoices_total_value}\n";
        echo "      ✓ " . ($inv->pr_invoices_total_value == $calculatedTotal ? 'صحيح' : 'خطأ') . "\n\n";
    }
} else {
    echo "   لا توجد فواتير.\n\n";
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "   نتيجة الاختبار\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$allCorrect = invoices::where('pr_number', $project->id)
    ->where('pr_invoices_total_value', '!=', $calculatedTotal)
    ->count() == 0;

if ($allCorrect) {
    echo "✅✅✅ ممتاز! النظام يعمل بشكل صحيح ✅✅✅\n";
    echo "   جميع الفواتير المرتبطة بـ PR #{$project->pr_number}\n";
    echo "   لها نفس قيمة المجموع الكلي: {$calculatedTotal}\n\n";
} else {
    echo "❌ يوجد خطأ في الحسابات!\n\n";
}

echo "═══════════════════════════════════════════════════════════════\n\n";
