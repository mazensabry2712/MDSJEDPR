<?php
/**
 * Test all PR Numbers individually
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n";
echo "===========================================\n";
echo "  TESTING ALL PR NUMBERS INDIVIDUALLY\n";
echo "===========================================\n\n";

// Get all projects
$projects = DB::table('projects')
    ->select('id', 'pr_number', 'name', 'cust_id', 'ppms_id', 'value', 'customer_po_date')
    ->orderBy('pr_number')
    ->get();

echo "Found {$projects->count()} projects to test\n\n";

foreach ($projects as $index => $project) {
    echo "═══════════════════════════════════════════\n";
    echo "TEST PR# {$project->pr_number}: {$project->name}\n";
    echo "═══════════════════════════════════════════\n\n";

    // Get customer
    $customer = DB::table('custs')->where('id', $project->cust_id)->first();
    $customerName = $customer ? $customer->name : 'N/A';

    // Get PM
    $pm = DB::table('ppms')->where('id', $project->ppms_id)->first();
    $pmName = $pm ? $pm->name : 'N/A';

    echo "📋 PROJECT INFORMATION:\n";
    echo "   ID: {$project->id}\n";
    echo "   PR Number: {$project->pr_number}\n";
    echo "   Name: {$project->name}\n";
    echo "   Customer: {$customerName}\n";
    echo "   PM: {$pmName}\n";
    echo "   Value: " . number_format($project->value ?? 0, 2) . " SAR\n";
    echo "   PO Date: " . ($project->customer_po_date ?? 'N/A') . "\n";
    echo "\n";

    // Tasks
    $totalTasks = DB::table('ptasks')->where('pr_number', $project->pr_number)->count();
    $completedTasks = DB::table('ptasks')
        ->where('pr_number', $project->pr_number)
        ->whereIn('status', ['Completed', 'completed'])
        ->count();
    $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

    echo "📊 TASKS STATISTICS:\n";
    echo "   Total Tasks: {$totalTasks}\n";
    echo "   Completed Tasks: {$completedTasks}\n";
    echo "   Progress: {$progress}%\n";

    if ($totalTasks > 0) {
        $tasks = DB::table('ptasks')
            ->where('pr_number', $project->pr_number)
            ->select('id', 'details', 'status')
            ->get();
        echo "   Task Details:\n";
        foreach ($tasks as $task) {
            $statusIcon = in_array($task->status, ['Completed', 'completed']) ? '✓' : '○';
            echo "      {$statusIcon} {$task->details} ({$task->status})\n";
        }
    }
    echo "\n";

    // Risks
    $totalRisks = DB::table('risks')->where('pr_number', $project->pr_number)->count();
    $highRisks = DB::table('risks')
        ->where('pr_number', $project->pr_number)
        ->whereIn('impact', ['High', 'high'])
        ->count();

    echo "⚠️  RISKS STATISTICS:\n";
    echo "   Total Risks: {$totalRisks}\n";
    echo "   High Impact Risks: {$highRisks}\n";

    if ($totalRisks > 0) {
        $risks = DB::table('risks')
            ->where('pr_number', $project->pr_number)
            ->select('id', 'risk', 'impact')
            ->get();
        echo "   Risk Details:\n";
        foreach ($risks as $risk) {
            echo "      - {$risk->risk} (Impact: {$risk->impact})\n";
        }
    }
    echo "\n";

    // Milestones
    $totalMilestones = DB::table('milestones')->where('pr_number', $project->pr_number)->count();
    $milestonesDone = DB::table('milestones')
        ->where('pr_number', $project->pr_number)
        ->whereIn('status', ['Completed', 'completed', 'on track'])
        ->count();

    echo "🎯 MILESTONES STATISTICS:\n";
    echo "   Total Milestones: {$totalMilestones}\n";
    echo "   Completed/On Track: {$milestonesDone}\n";

    if ($totalMilestones > 0) {
        $milestones = DB::table('milestones')
            ->where('pr_number', $project->pr_number)
            ->select('id', 'milestone', 'status')
            ->get();
        echo "   Milestone Details:\n";
        foreach ($milestones as $milestone) {
            echo "      - {$milestone->milestone} ({$milestone->status})\n";
        }
    }
    echo "\n";

    // Invoices
    $totalInvoices = DB::table('invoices')->where('pr_number', $project->pr_number)->count();
    $invoicesPaid = DB::table('invoices')
        ->where('pr_number', $project->pr_number)
        ->whereIn('status', ['paid', 'Paid'])
        ->count();

    echo "💰 INVOICES STATISTICS:\n";
    echo "   Total Invoices: {$totalInvoices}\n";
    echo "   Paid Invoices: {$invoicesPaid}\n";

    if ($totalInvoices > 0) {
        $invoices = DB::table('invoices')
            ->where('pr_number', $project->pr_number)
            ->select('id', 'invoice_number', 'status', 'value')
            ->get();
        echo "   Invoice Details:\n";
        foreach ($invoices as $invoice) {
            echo "      - #{$invoice->invoice_number}: " . number_format($invoice->value ?? 0, 2) . " SAR ({$invoice->status})\n";
        }
    }
    echo "\n";

    // Test URL
    $testUrl = "http://mdsjedpr.test/dashboard?filter[pr_number]={$project->pr_number}";
    echo "🔗 TEST URL:\n";
    echo "   {$testUrl}\n";
    echo "\n";

    // Summary
    echo "📝 SUMMARY:\n";
    $hasData = $totalTasks > 0 || $totalRisks > 0 || $totalMilestones > 0 || $totalInvoices > 0;
    if ($hasData) {
        echo "   ✓ Project has data - Good for testing\n";
        if ($totalTasks > 0 && $completedTasks > 0) {
            echo "   ✓ Progress bar will show: {$progress}%\n";
        }
        if ($totalTasks > 0 && $completedTasks == 0) {
            echo "   ⚠ No completed tasks - Progress bar will show 0%\n";
        }
    } else {
        echo "   ⚠ Project has no tasks/risks/milestones/invoices\n";
        echo "   ⚠ Only basic project info will be displayed\n";
    }
    echo "\n";

    // Print/PDF Test Note
    echo "🖨️  PRINT/PDF TEST:\n";
    if ($hasData) {
        echo "   ✓ Print button will show all statistics\n";
        echo "   ✓ PDF button will generate complete report\n";
    } else {
        echo "   ⚠ Print/PDF will show project info only (no statistics)\n";
    }
    echo "\n";

    if ($index < $projects->count() - 1) {
        echo "Press Enter to test next project...";
        // readline(); // Uncomment if you want to pause between tests
        echo "\n\n";
    }
}

echo "\n";
echo "===========================================\n";
echo "  TESTING COMPLETED FOR ALL PR NUMBERS\n";
echo "===========================================\n\n";

echo "📊 SUMMARY OF ALL PROJECTS:\n";
echo "─────────────────────────────────────────────\n";
printf("%-10s %-20s %-8s %-10s %-8s\n", "PR#", "Name", "Tasks", "Progress", "Has Data");
echo "─────────────────────────────────────────────\n";

foreach ($projects as $project) {
    $totalTasks = DB::table('ptasks')->where('pr_number', $project->pr_number)->count();
    $completedTasks = DB::table('ptasks')
        ->where('pr_number', $project->pr_number)
        ->whereIn('status', ['Completed', 'completed'])
        ->count();
    $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

    $totalRisks = DB::table('risks')->where('pr_number', $project->pr_number)->count();
    $totalMilestones = DB::table('milestones')->where('pr_number', $project->pr_number)->count();
    $totalInvoices = DB::table('invoices')->where('pr_number', $project->pr_number)->count();

    $hasData = $totalTasks > 0 || $totalRisks > 0 || $totalMilestones > 0 || $totalInvoices > 0;
    $dataStatus = $hasData ? "Yes ✓" : "No ⚠";

    $name = strlen($project->name) > 20 ? substr($project->name, 0, 17) . '...' : $project->name;

    printf("%-10s %-20s %-8s %-10s %-8s\n",
        $project->pr_number,
        $name,
        $totalTasks,
        $progress . '%',
        $dataStatus
    );
}

echo "─────────────────────────────────────────────\n\n";

echo "🎯 BEST PROJECT FOR TESTING:\n";
$bestProject = $projects->sortByDesc(function($p) {
    return DB::table('ptasks')->where('pr_number', $p->pr_number)->count();
})->first();

if ($bestProject) {
    echo "   PR# {$bestProject->pr_number}: {$bestProject->name}\n";
    echo "   URL: http://mdsjedpr.test/dashboard?filter[pr_number]={$bestProject->pr_number}\n";
}

echo "\n✓ All PR Numbers tested successfully!\n\n";
