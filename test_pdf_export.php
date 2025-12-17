<?php
/**
 * Test Script for TCPDF Export
 *
 * This script tests the new PDF export functionality using TCPDF library.
 * It will generate a test URL that you can use in your browser.
 */

echo "\n";
echo "╔══════════════════════════════════════════════════════════════════════════╗\n";
echo "║                    TCPDF EXPORT TEST SCRIPT                              ║\n";
echo "╚══════════════════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Database connection
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Load Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "📊 Testing TCPDF PDF Export Functionality\n";
echo str_repeat("─", 76) . "\n\n";

// Get projects with data
$projects = DB::table('projects')
    ->select('id', 'pr_number', 'name')
    ->whereIn('id', [1, 2, 3, 4, 5])
    ->orderBy('pr_number')
    ->get();

echo "✅ Found " . count($projects) . " projects in database\n\n";

echo "🔗 TEST URLS FOR PDF EXPORT:\n";
echo str_repeat("─", 76) . "\n";

foreach ($projects as $project) {
    $url = "http://mdsjedpr.test/dashboard/export/pdf/" . $project->pr_number;

    // Count related records
    $tasksCount = DB::table('ptasks')->where('pr_number', $project->id)->count();
    $risksCount = DB::table('risks')->where('pr_number', $project->id)->count();
    $milestonesCount = DB::table('milestones')->where('pr_number', $project->id)->count();
    $invoicesCount = DB::table('invoices')->where('pr_number', $project->id)->count();

    echo "\n📄 PR# {$project->pr_number}: {$project->name}\n";
    echo "   URL: {$url}\n";
    echo "   Data: {$tasksCount} tasks, {$risksCount} risks, {$milestonesCount} milestones, {$invoicesCount} invoices\n";
}

echo "\n";
echo str_repeat("─", 76) . "\n";
echo "\n";

echo "📋 TESTING INSTRUCTIONS:\n";
echo "1. Open any URL above in your browser\n";
echo "2. The PDF should download automatically\n";
echo "3. Check that the PDF contains:\n";
echo "   ✓ Project name and PR number\n";
echo "   ✓ Customer, PM, Value, PO Date\n";
echo "   ✓ Progress bar with percentage\n";
echo "   ✓ Completed vs Total tasks\n";
echo "   ✓ Statistics cards (Tasks, Risks, Milestones, Invoices)\n";
echo "   ✓ Footer with date/time stamp\n";
echo "\n";

echo "🎯 BEST PROJECTS FOR TESTING:\n";
echo str_repeat("─", 76) . "\n";

// Find projects with most data
$bestProjects = DB::table('projects')
    ->select('projects.id', 'projects.pr_number', 'projects.name')
    ->selectRaw('
        (SELECT COUNT(*) FROM ptasks WHERE ptasks.pr_number = projects.id) as tasks_count,
        (SELECT COUNT(*) FROM risks WHERE risks.pr_number = projects.id) as risks_count,
        (SELECT COUNT(*) FROM milestones WHERE milestones.pr_number = projects.id) as milestones_count,
        (SELECT COUNT(*) FROM invoices WHERE invoices.pr_number = projects.id) as invoices_count
    ')
    ->havingRaw('(tasks_count + risks_count + milestones_count + invoices_count) > 0')
    ->orderByRaw('(tasks_count + risks_count + milestones_count + invoices_count) DESC')
    ->limit(3)
    ->get();

foreach ($bestProjects as $i => $project) {
    $totalData = $project->tasks_count + $project->risks_count + $project->milestones_count + $project->invoices_count;
    echo "\n" . ($i + 1) . ". PR# {$project->pr_number} ({$totalData} items)\n";
    echo "   http://mdsjedpr.test/dashboard/export/pdf/{$project->pr_number}\n";
}

echo "\n";
echo str_repeat("═", 76) . "\n";
echo "\n";

echo "✅ IMPROVEMENTS WITH TCPDF:\n";
echo "   • Server-side generation (faster, more reliable)\n";
echo "   • Better Arabic text support\n";
echo "   • Professional PDF quality\n";
echo "   • No browser compatibility issues\n";
echo "   • Consistent formatting across devices\n";
echo "   • Smaller file sizes with compression\n";
echo "\n";

echo "📌 NOTE: Make sure you're logged in to the system before testing!\n";
echo "\n";
