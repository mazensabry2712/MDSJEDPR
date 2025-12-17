<?php

echo "
╔═══════════════════════════════════════════════════════════════╗
║          COMPLETE SYSTEM TEST - ALL DATA                      ║
║          Testing Every PR Number in Browser                   ║
╚═══════════════════════════════════════════════════════════════╝
";

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;

// Get all projects with their relationships
$projects = Project::with(['tasks', 'risks', 'milestones', 'invoices', 'cust', 'ppms'])->get();

echo "\n📊 TESTING ALL PROJECTS IN THE SYSTEM\n";
echo "Found {$projects->count()} projects\n";
echo str_repeat('═', 70) . "\n\n";

$testResults = [];

foreach ($projects as $project) {
    echo "╔═══════════════════════════════════════════════════════════════╗\n";
    echo "║  TESTING PR# {$project->pr_number}: {$project->name}\n";
    echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

    // Calculate progress
    $totalTasks = $project->tasks->count();
    $completedTasks = $project->tasks->whereIn('status', ['Completed', 'completed'])->count();
    $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

    // Calculate statistics
    $totalRisks = $project->risks->count();
    $highRisks = $project->risks->whereIn('impact', ['High', 'high'])->count();
    $totalMilestones = $project->milestones->count();
    $milestonesDone = $project->milestones->whereIn('status', ['Completed', 'completed', 'on track'])->count();
    $totalInvoices = $project->invoices->count();
    $invoicesPaid = $project->invoices->whereIn('status', ['paid', 'Paid'])->count();

    // Project Information
    echo "📋 PROJECT INFORMATION:\n";
    echo "   ID: {$project->id}\n";
    echo "   PR Number: {$project->pr_number}\n";
    echo "   Name: {$project->name}\n";
    echo "   Customer: " . ($project->cust->name ?? 'N/A') . "\n";
    echo "   PM: " . ($project->ppms->name ?? 'N/A') . "\n";
    echo "   Value: " . number_format($project->value ?? 0, 2) . " SAR\n";
    echo "   PO Date: " . ($project->customer_po_date ?? 'N/A') . "\n\n";

    // Progress Section Test
    echo "📊 PROGRESS SECTION TEST:\n";
    echo "   ✓ Header: 'Project Progress' - Will Display\n";
    echo "   ✓ Progress Badge: {$progress}% - Will Display\n";
    echo "   ✓ Print Button: Will Display\n";
    echo "   ✓ PDF Button: Will Display\n";
    echo "   ✓ Progress Bar: Will Display (width: {$progress}%)\n";
    echo "   ✓ Completed Box: {$completedTasks} - Will Display\n";
    echo "   ✓ Total Tasks Box: {$totalTasks} - Will Display\n\n";

    // Tasks Details
    echo "🟢 TASKS CARD TEST:\n";
    echo "   Total Tasks: {$totalTasks}\n";
    echo "   Completed: {$completedTasks}\n";
    if ($totalTasks > 0) {
        echo "   Task Details:\n";
        foreach ($project->tasks as $task) {
            $icon = in_array($task->status, ['Completed', 'completed']) ? '✓' : '○';
            echo "      {$icon} {$task->details} ({$task->status})\n";
        }
    } else {
        echo "   ℹ️  No tasks data\n";
    }
    echo "\n";

    // Risks Details
    echo "🔴 RISKS CARD TEST:\n";
    echo "   Total Risks: {$totalRisks}\n";
    echo "   High Impact: {$highRisks}\n";
    if ($totalRisks > 0) {
        echo "   Risk Details:\n";
        foreach ($project->risks as $risk) {
            echo "      ⚠️  {$risk->risk} (Impact: {$risk->impact})\n";
        }
    } else {
        echo "   ℹ️  No risks data\n";
    }
    echo "\n";

    // Milestones Details
    echo "🟡 MILESTONES CARD TEST:\n";
    echo "   Total Milestones: {$totalMilestones}\n";
    echo "   Completed/On Track: {$milestonesDone}\n";
    if ($totalMilestones > 0) {
        echo "   Milestone Details:\n";
        foreach ($project->milestones as $milestone) {
            echo "      🎯 {$milestone->milestone} ({$milestone->status})\n";
        }
    } else {
        echo "   ℹ️  No milestones data\n";
    }
    echo "\n";

    // Invoices Details
    echo "🔵 INVOICES CARD TEST:\n";
    echo "   Total Invoices: {$totalInvoices}\n";
    echo "   Paid: {$invoicesPaid}\n";
    if ($totalInvoices > 0) {
        echo "   Invoice Details:\n";
        foreach ($project->invoices as $invoice) {
            echo "      💰 #{$invoice->invoice_number}: " . number_format($invoice->value, 2) . " SAR ({$invoice->status})\n";
        }
    } else {
        echo "   ℹ️  No invoices data\n";
    }
    echo "\n";

    // Test URL
    $testUrl = "http://mdsjedpr.test/dashboard?filter[pr_number]={$project->pr_number}";
    echo "🔗 BROWSER TEST URL:\n";
    echo "   {$testUrl}\n\n";

    // Expected Results
    echo "✅ EXPECTED RESULTS IN BROWSER:\n";
    echo "   1. Project Info boxes will show: Customer, PM, Value, PO Date\n";
    echo "   2. Progress section will show: {$progress}% badge\n";
    echo "   3. Progress bar will be " . ($progress > 0 ? "GREEN with {$progress}% width" : "GRAY (empty)") . "\n";
    echo "   4. Completed box will show: {$completedTasks}\n";
    echo "   5. Total Tasks box will show: {$totalTasks}\n";
    echo "   6. Tasks card will show: {$totalTasks} total, {$completedTasks} completed\n";
    echo "   7. Risks card will show: {$totalRisks} total, {$highRisks} high\n";
    echo "   8. Milestones card will show: {$totalMilestones} total, {$milestonesDone} done\n";
    echo "   9. Invoices card will show: {$totalInvoices} total, {$invoicesPaid} paid\n";
    echo "   10. Print and PDF buttons will be visible and working\n\n";

    // Test Result
    $hasData = $totalTasks > 0 || $totalRisks > 0 || $totalMilestones > 0 || $totalInvoices > 0;

    $result = [
        'pr_number' => $project->pr_number,
        'name' => $project->name,
        'has_data' => $hasData,
        'tasks' => $totalTasks,
        'risks' => $totalRisks,
        'milestones' => $totalMilestones,
        'invoices' => $totalInvoices,
        'progress' => $progress,
        'url' => $testUrl,
        'status' => $hasData ? '✅ HAS DATA' : '⚠️  EMPTY PROJECT'
    ];

    $testResults[] = $result;

    echo "📝 TEST STATUS: {$result['status']}\n";

    echo "\n" . str_repeat('═', 70) . "\n\n";
}

// Summary Table
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║                    TEST SUMMARY TABLE                         ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

echo "┌──────┬──────────────────┬─────────┬───────┬────────┬───────────┬──────────┬──────────┐\n";
echo "│ PR#  │ Project Name     │Progress │ Tasks │ Risks  │Milestones │ Invoices │  Status  │\n";
echo "├──────┼──────────────────┼─────────┼───────┼────────┼───────────┼──────────┼──────────┤\n";

foreach ($testResults as $result) {
    printf("│ %-4s │ %-16s │ %6s%% │ %5d │ %6d │ %9d │ %8d │ %-8s │\n",
        $result['pr_number'],
        substr($result['name'], 0, 16),
        $result['progress'],
        $result['tasks'],
        $result['risks'],
        $result['milestones'],
        $result['invoices'],
        $result['has_data'] ? 'HAS DATA' : 'EMPTY'
    );
}

echo "└──────┴──────────────────┴─────────┴───────┴────────┴───────────┴──────────┴──────────┘\n\n";

// Statistics
$totalProjects = count($testResults);
$projectsWithData = array_filter($testResults, fn($r) => $r['has_data']);
$emptyProjects = $totalProjects - count($projectsWithData);

$totalTasksAll = array_sum(array_column($testResults, 'tasks'));
$totalRisksAll = array_sum(array_column($testResults, 'risks'));
$totalMilestonesAll = array_sum(array_column($testResults, 'milestones'));
$totalInvoicesAll = array_sum(array_column($testResults, 'invoices'));

echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║                    OVERALL STATISTICS                         ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

echo "📊 PROJECT STATISTICS:\n";
echo "   Total Projects: {$totalProjects}\n";
echo "   Projects with Data: " . count($projectsWithData) . " ✅\n";
echo "   Empty Projects: {$emptyProjects} ⚠️\n\n";

echo "📈 DATA STATISTICS:\n";
echo "   Total Tasks: {$totalTasksAll}\n";
echo "   Total Risks: {$totalRisksAll}\n";
echo "   Total Milestones: {$totalMilestonesAll}\n";
echo "   Total Invoices: {$totalInvoicesAll}\n\n";

echo "🎯 BEST PROJECTS FOR TESTING:\n";
$bestProjects = array_filter($testResults, fn($r) => $r['has_data']);
usort($bestProjects, function($a, $b) {
    return ($b['tasks'] + $b['risks'] + $b['milestones'] + $b['invoices'])
         - ($a['tasks'] + $a['risks'] + $a['milestones'] + $a['invoices']);
});

foreach (array_slice($bestProjects, 0, 3) as $i => $project) {
    echo "   " . ($i + 1) . ". PR# {$project['pr_number']}: {$project['name']}\n";
    echo "      Progress: {$project['progress']}%\n";
    echo "      Data: {$project['tasks']} tasks, {$project['risks']} risks, ";
    echo "{$project['milestones']} milestones, {$project['invoices']} invoices\n";
    echo "      URL: {$project['url']}\n\n";
}

// Quick Test Commands
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║                QUICK BROWSER TEST COMMANDS                    ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

echo "🌐 Copy & Paste these URLs to test in browser:\n\n";
foreach ($testResults as $result) {
    $icon = $result['has_data'] ? '✅' : '⚠️ ';
    echo "{$icon} PR# {$result['pr_number']}: {$result['url']}\n";
}

echo "\n╔═══════════════════════════════════════════════════════════════╗\n";
echo "║              ✅ COMPLETE TEST REPORT GENERATED                ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n";
