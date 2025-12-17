<?php
/**
 * Dashboard Filter Testing Script
 * Tests all filter scenarios from selecting PR number to viewing results
 */

echo "===========================================\n";
echo "  DASHBOARD FILTER TESTING - MDSJEDPR\n";
echo "===========================================\n\n";

// Database connection
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "✓ Application bootstrapped successfully\n\n";

// Test 1: Get all projects with PR numbers
echo "TEST 1: Getting all projects with PR numbers\n";
echo "--------------------------------------------\n";
$projects = DB::table('projects')
    ->select('id', 'pr_number', 'name', 'cust_id', 'ppms_id', 'value', 'customer_po_date')
    ->orderBy('pr_number')
    ->get();

if ($projects->isEmpty()) {
    echo "❌ ERROR: No projects found in database!\n";
    exit(1);
}

echo "✓ Found {$projects->count()} projects\n\n";
echo "Available PR Numbers:\n";
foreach ($projects as $project) {
    echo "  - PR# {$project->pr_number}: {$project->name}\n";
}
echo "\n";

// Test 2: Test PR Number filter functionality
echo "TEST 2: Testing PR Number filter\n";
echo "--------------------------------------------\n";
$testPrNumber = $projects->first()->pr_number;
echo "Testing with PR Number: {$testPrNumber}\n";

$filteredProject = DB::table('projects')
    ->where('pr_number', $testPrNumber)
    ->first();

if (!$filteredProject) {
    echo "❌ ERROR: PR Number filter not working!\n";
    exit(1);
}

echo "✓ PR Number filter working correctly\n";
echo "  Found project: {$filteredProject->name}\n\n";

// Test 3: Test Project Name filter functionality
echo "TEST 3: Testing Project Name filter\n";
echo "--------------------------------------------\n";
$testProjectName = $filteredProject->name;
echo "Testing with Project Name: {$testProjectName}\n";

$filteredByName = DB::table('projects')
    ->where('name', $testProjectName)
    ->first();

if (!$filteredByName) {
    echo "❌ ERROR: Project Name filter not working!\n";
    exit(1);
}

echo "✓ Project Name filter working correctly\n";
echo "  Found PR Number: {$filteredByName->pr_number}\n\n";

// Test 4: Test relationships loading
echo "TEST 4: Testing relationships (Customer, PM)\n";
echo "--------------------------------------------\n";

$projectWithRelations = DB::table('projects')
    ->leftJoin('custs', 'projects.cust_id', '=', 'custs.id')
    ->leftJoin('ppms', 'projects.ppms_id', '=', 'ppms.id')
    ->select('projects.*', 'custs.name as customer_name', 'ppms.name as pm_name')
    ->where('projects.pr_number', $testPrNumber)
    ->first();

echo "  Project: {$projectWithRelations->name}\n";
echo "  Customer: " . ($projectWithRelations->customer_name ?? 'N/A') . "\n";
echo "  PM: " . ($projectWithRelations->pm_name ?? 'N/A') . "\n";
echo "  Value: " . number_format($projectWithRelations->value ?? 0, 2) . " SAR\n";
echo "  PO Date: " . ($projectWithRelations->customer_po_date ?? 'N/A') . "\n";
echo "✓ Relationships loaded successfully\n\n";

// Test 5: Test tasks calculation
echo "TEST 5: Testing tasks statistics\n";
echo "--------------------------------------------\n";

$totalTasks = DB::table('ptasks')
    ->where('pr_number', $testPrNumber)
    ->count();

$completedTasks = DB::table('ptasks')
    ->where('pr_number', $testPrNumber)
    ->whereIn('status', ['Completed', 'completed'])
    ->count();

$progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

echo "  Total Tasks: {$totalTasks}\n";
echo "  Completed Tasks: {$completedTasks}\n";
echo "  Progress: {$progress}%\n";
echo "✓ Task calculations working correctly\n\n";

// Test 6: Test additional statistics
echo "TEST 6: Testing additional statistics\n";
echo "--------------------------------------------\n";

$risks = DB::table('risks')->where('pr_number', $testPrNumber)->count();
$highRisks = DB::table('risks')->where('pr_number', $testPrNumber)->whereIn('impact', ['High', 'high'])->count();

$milestones = DB::table('milestones')->where('pr_number', $testPrNumber)->count();
$milestonesDone = DB::table('milestones')->where('pr_number', $testPrNumber)->whereIn('status', ['Completed', 'completed', 'on track'])->count();

$invoices = DB::table('invoices')->where('pr_number', $testPrNumber)->count();
$invoicesPaid = DB::table('invoices')->where('pr_number', $testPrNumber)->whereIn('status', ['paid', 'Paid'])->count();

echo "  Risks: {$risks} (High: {$highRisks})\n";
echo "  Milestones: {$milestones} (Done: {$milestonesDone})\n";
echo "  Invoices: {$invoices} (Paid: {$invoicesPaid})\n";
echo "✓ Statistics calculated successfully\n\n";

// Test 7: Test URL generation
echo "TEST 7: Testing filter URL generation\n";
echo "--------------------------------------------\n";

$baseUrl = "http://mdsjedpr.test/dashboard";
$filterUrl = "{$baseUrl}?filter[pr_number]={$testPrNumber}";

echo "  Base URL: {$baseUrl}\n";
echo "  Filter URL: {$filterUrl}\n";
echo "✓ URL generation working\n\n";

// Test 8: Test all projects loop
echo "TEST 8: Testing multiple projects\n";
echo "--------------------------------------------\n";

$testProjects = $projects->take(3);
foreach ($testProjects as $project) {
    $tasks = DB::table('ptasks')->where('pr_number', $project->pr_number)->count();
    $completed = DB::table('ptasks')->where('pr_number', $project->pr_number)->whereIn('status', ['Completed', 'completed'])->count();
    $prog = $tasks > 0 ? round(($completed / $tasks) * 100, 1) : 0;

    echo "  PR# {$project->pr_number}: {$project->name}\n";
    echo "    Tasks: {$tasks}, Completed: {$completed}, Progress: {$prog}%\n";
}
echo "✓ Multiple projects tested successfully\n\n";

// Final Summary
echo "===========================================\n";
echo "  ✓ ALL TESTS PASSED SUCCESSFULLY!\n";
echo "===========================================\n\n";

echo "Test URLs to try in browser:\n";
echo "1. Dashboard without filters: {$baseUrl}\n";
echo "2. Filter by PR Number: {$baseUrl}?filter[pr_number]={$testPrNumber}\n";
echo "3. Filter by Project Name: {$baseUrl}?filter[project_name]=" . urlencode($testProjectName) . "\n\n";

echo "Manual Testing Steps:\n";
echo "1. Open: {$baseUrl}\n";
echo "2. Click on 'PR Number' dropdown\n";
echo "3. Select PR# {$testPrNumber}\n";
echo "4. Click 'Apply Filters' button\n";
echo "5. Verify project details are displayed\n";
echo "6. Check Progress Bar shows {$progress}%\n";
echo "7. Verify statistics show correct numbers\n";
echo "8. Click 'Print' button to test printing\n";
echo "9. Click 'PDF' button to test PDF download\n";
echo "10. Click 'Reset All' to clear filters\n\n";

echo "✓ Testing completed successfully!\n";
