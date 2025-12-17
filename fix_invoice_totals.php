<?php

/**
 * FIX EXISTING INVOICES - Update all pr_invoices_total_value
 * تحديث جميع الفواتير الموجودة مسبقاً
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\invoices;
use App\Models\Project;

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "   تحديث قيم PR Invoices Total Value للفواتير الموجودة\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Get all unique pr_numbers from invoices
$prNumbers = invoices::distinct()->pluck('pr_number');

echo "📊 عدد المشاريع التي لها فواتير: " . $prNumbers->count() . "\n\n";

$updatedCount = 0;

foreach ($prNumbers as $prNumber) {
    $invoicesForPr = invoices::where('pr_number', $prNumber)->get();
    $totalValue = $invoicesForPr->sum('value');

    // Get project name for display
    $project = Project::find($prNumber);
    $projectName = $project ? $project->name : "Unknown";

    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "PR #{$prNumber} - {$projectName}\n";
    echo "   عدد الفواتير: " . $invoicesForPr->count() . "\n";
    echo "   المجموع الكلي: {$totalValue}\n";

    // Update all invoices for this PR
    $updated = invoices::where('pr_number', $prNumber)
        ->update(['pr_invoices_total_value' => $totalValue]);

    echo "   ✅ تم تحديث {$updated} فاتورة\n\n";

    $updatedCount += $updated;
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "   النتيجة النهائية\n";
echo "═══════════════════════════════════════════════════════════════\n\n";
echo "✅ تم تحديث {$updatedCount} فاتورة بنجاح!\n";
echo "✅ جميع الفواتير الآن لها قيمة pr_invoices_total_value صحيحة\n\n";

// Verify
echo "═══════════════════════════════════════════════════════════════\n";
echo "   التحقق من النتائج\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

foreach ($prNumbers as $prNumber) {
    $invoicesForPr = invoices::where('pr_number', $prNumber)->get();
    $expectedTotal = $invoicesForPr->sum('value');

    $project = Project::find($prNumber);
    $projectName = $project ? $project->name : "Unknown";

    echo "PR #{$prNumber} - {$projectName}:\n";

    foreach ($invoicesForPr as $inv) {
        $status = $inv->pr_invoices_total_value == $expectedTotal ? '✅' : '❌';
        echo "   {$status} Invoice #{$inv->invoice_number}: Value={$inv->value}, Total={$inv->pr_invoices_total_value}\n";
    }
    echo "\n";
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "✅ اكتمل التحديث بنجاح!\n";
echo "═══════════════════════════════════════════════════════════════\n\n";
