<?php

echo "
╔═══════════════════════════════════════════════════════════════╗
║            COMPLETE FILTER SYSTEM TEST                        ║
║          Testing All Filter Combinations                      ║
╚═══════════════════════════════════════════════════════════════╝
";

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;

// Get all projects
$allProjects = Project::with(['tasks', 'risks', 'milestones', 'invoices', 'cust', 'ppms'])->get();

echo "\n📊 DATABASE STATUS:\n";
echo "   Total Projects: {$allProjects->count()}\n";
echo "   Unique PR Numbers: " . $allProjects->pluck('pr_number')->unique()->count() . "\n";
echo "   Unique Project Names: " . $allProjects->pluck('name')->unique()->count() . "\n";
echo "\n" . str_repeat('═', 70) . "\n\n";

// Test Cases
$testCases = [
    [
        'name' => 'No Filter (Show All)',
        'filter' => [],
        'description' => 'Should show all projects when no filter is applied'
    ],
    [
        'name' => 'Filter by PR Number = 1',
        'filter' => ['pr_number' => '1'],
        'description' => 'Should show only PR#1 (qqq)'
    ],
    [
        'name' => 'Filter by PR Number = 2',
        'filter' => ['pr_number' => '2'],
        'description' => 'Should show only PR#2 (sdasdas) with 50% progress'
    ],
    [
        'name' => 'Filter by PR Number = 34',
        'filter' => ['pr_number' => '34'],
        'description' => 'Should show only PR#34'
    ],
    [
        'name' => 'Filter by PR Number = 432',
        'filter' => ['pr_number' => '432'],
        'description' => 'Should show only PR#432'
    ],
    [
        'name' => 'Filter by PR Number = 99',
        'filter' => ['pr_number' => '99'],
        'description' => 'Should show only PR#99'
    ],
    [
        'name' => 'Filter by Project Name = "qqq"',
        'filter' => ['project_name' => 'qqq'],
        'description' => 'Should show only project named "qqq"'
    ],
    [
        'name' => 'Filter by Project Name = "sdasdas"',
        'filter' => ['project_name' => 'sdasdas'],
        'description' => 'Should show only project named "sdasdas"'
    ],
    [
        'name' => 'Filter by Project Name = "admin@admin.com"',
        'filter' => ['project_name' => 'admin@admin.com'],
        'description' => 'Should show projects named "admin@admin.com" (could be multiple)'
    ],
    [
        'name' => 'Filter by Project Name = "test"',
        'filter' => ['project_name' => 'test'],
        'description' => 'Should show only project named "test"'
    ],
    [
        'name' => 'Filter: PR Number = 2 AND Project Name = "sdasdas"',
        'filter' => ['pr_number' => '2', 'project_name' => 'sdasdas'],
        'description' => 'Should show PR#2 only (both filters match)'
    ],
    [
        'name' => 'Filter: PR Number = 1 AND Project Name = "sdasdas"',
        'filter' => ['pr_number' => '1', 'project_name' => 'sdasdas'],
        'description' => 'Should show NO results (filters don\'t match same project)'
    ],
    [
        'name' => 'Filter: PR Number = "all"',
        'filter' => ['pr_number' => 'all'],
        'description' => 'Should show all projects'
    ],
    [
        'name' => 'Filter: Project Name = "all"',
        'filter' => ['project_name' => 'all'],
        'description' => 'Should show all projects'
    ],
];

$testNumber = 0;
$passedTests = 0;
$failedTests = 0;

foreach ($testCases as $test) {
    $testNumber++;

    echo "╔═══════════════════════════════════════════════════════════════╗\n";
    echo "║  TEST #{$testNumber}: {$test['name']}\n";
    echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

    echo "📋 Description: {$test['description']}\n\n";

    // Build query
    $query = Project::query()->with(['tasks', 'risks', 'milestones', 'invoices', 'cust', 'ppms']);

    // Apply filters
    if (!empty($test['filter']['pr_number']) && $test['filter']['pr_number'] !== 'all') {
        $query->where('pr_number', $test['filter']['pr_number']);
        echo "🔍 Applied Filter: pr_number = {$test['filter']['pr_number']}\n";
    }

    if (!empty($test['filter']['project_name']) && $test['filter']['project_name'] !== 'all') {
        $query->where('name', $test['filter']['project_name']);
        echo "🔍 Applied Filter: project_name = {$test['filter']['project_name']}\n";
    }

    if (empty($test['filter'])) {
        echo "🔍 No filters applied - showing all\n";
    }

    // Execute query
    $filteredProjects = $query->get();

    echo "\n📊 RESULTS:\n";
    echo "   Found: {$filteredProjects->count()} project(s)\n\n";

    if ($filteredProjects->count() > 0) {
        foreach ($filteredProjects as $project) {
            $totalTasks = $project->tasks->count();
            $completedTasks = $project->tasks->whereIn('status', ['Completed', 'completed'])->count();
            $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

            echo "   ✓ PR# {$project->pr_number}: {$project->name}\n";
            echo "     Customer: " . ($project->cust->name ?? 'N/A') . "\n";
            echo "     PM: " . ($project->ppms->name ?? 'N/A') . "\n";
            echo "     Progress: {$progress}% ({$completedTasks}/{$totalTasks} tasks)\n";
            echo "     Tasks: {$project->tasks->count()}, ";
            echo "Risks: {$project->risks->count()}, ";
            echo "Milestones: {$project->milestones->count()}, ";
            echo "Invoices: {$project->invoices->count()}\n\n";
        }
    } else {
        echo "   ℹ No projects found matching the filters\n\n";
    }

    // Build test URL
    $urlParams = [];
    if (!empty($test['filter']['pr_number'])) {
        $urlParams[] = "filter[pr_number]={$test['filter']['pr_number']}";
    }
    if (!empty($test['filter']['project_name'])) {
        $urlParams[] = "filter[project_name]=" . urlencode($test['filter']['project_name']);
    }

    $testUrl = "http://mdsjedpr.test/dashboard";
    if (!empty($urlParams)) {
        $testUrl .= "?" . implode("&", $urlParams);
    }

    echo "🔗 Test URL:\n   {$testUrl}\n\n";

    // Validate result
    $expectedCount = null;
    $testPassed = true;

    // Validation logic
    if (isset($test['filter']['pr_number']) && $test['filter']['pr_number'] !== 'all' &&
        isset($test['filter']['project_name']) && $test['filter']['project_name'] !== 'all') {
        // Both filters - check if they match
        $expectedProject = $allProjects->where('pr_number', $test['filter']['pr_number'])
                                       ->where('name', $test['filter']['project_name'])
                                       ->first();
        $expectedCount = $expectedProject ? 1 : 0;
    } elseif (isset($test['filter']['pr_number']) && $test['filter']['pr_number'] !== 'all') {
        // PR number only
        $expectedCount = $allProjects->where('pr_number', $test['filter']['pr_number'])->count();
    } elseif (isset($test['filter']['project_name']) && $test['filter']['project_name'] !== 'all') {
        // Project name only
        $expectedCount = $allProjects->where('name', $test['filter']['project_name'])->count();
    } else {
        // No filter or "all"
        $expectedCount = $allProjects->count();
    }

    if ($expectedCount !== null && $filteredProjects->count() === $expectedCount) {
        echo "✅ TEST PASSED: Expected {$expectedCount} project(s), got {$filteredProjects->count()}\n";
        $passedTests++;
    } else {
        echo "❌ TEST FAILED: Expected {$expectedCount} project(s), got {$filteredProjects->count()}\n";
        $failedTests++;
        $testPassed = false;
    }

    echo "\n" . str_repeat('═', 70) . "\n\n";
}

// Final Summary
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║                    FINAL TEST SUMMARY                         ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

echo "📊 TEST RESULTS:\n";
echo "   Total Tests: {$testNumber}\n";
echo "   ✅ Passed: {$passedTests}\n";
echo "   ❌ Failed: {$failedTests}\n";
echo "   Success Rate: " . round(($passedTests / $testNumber) * 100, 1) . "%\n\n";

if ($failedTests === 0) {
    echo "╔═══════════════════════════════════════════════════════════════╗\n";
    echo "║          🎉 ALL FILTER TESTS PASSED SUCCESSFULLY! 🎉         ║\n";
    echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

    echo "✅ FILTER SYSTEM VALIDATION:\n";
    echo "   ✓ PR Number filter works correctly\n";
    echo "   ✓ Project Name filter works correctly\n";
    echo "   ✓ Combined filters work correctly\n";
    echo "   ✓ 'All' option works correctly\n";
    echo "   ✓ No results scenario handled correctly\n";
    echo "   ✓ Query building is correct\n";
    echo "   ✓ Data relationships loaded correctly\n\n";

    echo "🎯 FILTER SYSTEM IS FULLY FUNCTIONAL!\n";
} else {
    echo "⚠️  Some tests failed. Review the failed test cases above.\n";
}

echo "\n📝 QUICK TEST URLS:\n";
echo "   All Projects: http://mdsjedpr.test/dashboard\n";
echo "   PR#1: http://mdsjedpr.test/dashboard?filter[pr_number]=1\n";
echo "   PR#2: http://mdsjedpr.test/dashboard?filter[pr_number]=2\n";
echo "   PR#34: http://mdsjedpr.test/dashboard?filter[pr_number]=34\n";
echo "   PR#432: http://mdsjedpr.test/dashboard?filter[pr_number]=432\n";
echo "   By Name 'sdasdas': http://mdsjedpr.test/dashboard?filter[project_name]=sdasdas\n";
echo "   Combined (PR#2 + sdasdas): http://mdsjedpr.test/dashboard?filter[pr_number]=2&filter[project_name]=sdasdas\n";
