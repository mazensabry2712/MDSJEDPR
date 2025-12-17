<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\invoices;
use App\Models\Project;

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "   تشخيص كامل لجميع الفواتير\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Get all invoices
$allInvoices = invoices::with('project')->get();

echo "📊 إجمالي عدد الفواتير: " . $allInvoices->count() . "\n\n";

// Group by PR_NUMBER
$groupedByPr = $allInvoices->groupBy('pr_number');

foreach ($groupedByPr as $prNumber => $invoices) {
    $project = Project::find($prNumber);
    $projectName = $project ? $project->name : 'Unknown';
    $projectPrNumber = $project ? $project->pr_number : 'N/A';

    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🔹 Project ID: {$prNumber}\n";
    echo "   Project PR#: {$projectPrNumber}\n";
    echo "   Project Name: {$projectName}\n";
    echo "   عدد الفواتير: " . $invoices->count() . "\n\n";

    $totalValue = 0;
    foreach ($invoices as $invoice) {
        echo "   📄 Invoice #{$invoice->invoice_number}\n";
        echo "      - Value: {$invoice->value}\n";
        echo "      - PR Invoices Total Value: {$invoice->pr_invoices_total_value}\n";
        $totalValue += $invoice->value;
    }

    echo "\n   📊 المجموع الفعلي (يجب أن يكون): {$totalValue}\n";

    // Check if all invoices have correct total
    $correctCount = 0;
    foreach ($invoices as $invoice) {
        if ($invoice->pr_invoices_total_value == $totalValue) {
            $correctCount++;
        }
    }

    if ($correctCount == $invoices->count()) {
        echo "   ✅ جميع الفواتير صحيحة\n\n";
    } else {
        echo "   ❌ خطأ: {$correctCount} من {$invoices->count()} صحيحة فقط\n";
        echo "   🔧 سيتم التصحيح...\n\n";
    }
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "   تصحيح جميع القيم الآن...\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Fix all totals
$prNumbers = invoices::distinct()->pluck('pr_number');
$fixedCount = 0;

foreach ($prNumbers as $prNumber) {
    $total = invoices::where('pr_number', $prNumber)->sum('value');
    $updated = invoices::where('pr_number', $prNumber)
        ->update(['pr_invoices_total_value' => $total]);

    $project = Project::find($prNumber);
    $projectName = $project ? $project->name : 'Unknown';

    echo "✅ PR #{$prNumber} ({$projectName}): تم تحديث {$updated} فاتورة → Total = {$total}\n";
    $fixedCount += $updated;
}

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "   التحقق النهائي\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Final verification
$allInvoices = invoices::with('project')->get();
$allCorrect = true;

foreach ($groupedByPr as $prNumber => $invoices) {
    $expectedTotal = invoices::where('pr_number', $prNumber)->sum('value');
    $actualInvoices = invoices::where('pr_number', $prNumber)->get();

    $project = Project::find($prNumber);
    $projectName = $project ? $project->name : 'Unknown';

    echo "PR #{$prNumber} - {$projectName}:\n";

    foreach ($actualInvoices as $inv) {
        $status = $inv->pr_invoices_total_value == $expectedTotal ? '✅' : '❌';
        echo "   {$status} Invoice #{$inv->invoice_number}: Value={$inv->value}, Total={$inv->pr_invoices_total_value} (Expected: {$expectedTotal})\n";

        if ($inv->pr_invoices_total_value != $expectedTotal) {
            $allCorrect = false;
        }
    }
    echo "\n";
}

echo "═══════════════════════════════════════════════════════════════\n";
if ($allCorrect) {
    echo "✅✅✅ ممتاز! جميع الفواتير صحيحة الآن! ✅✅✅\n";
} else {
    echo "❌ لا يزال هناك خطأ - يحتاج تشخيص إضافي\n";
}
echo "═══════════════════════════════════════════════════════════════\n\n";

// Clear cache
echo "🔄 مسح الـ Cache...\n";
Illuminate\Support\Facades\Cache::flush();
echo "✅ تم مسح الـ Cache بنجاح!\n\n";
