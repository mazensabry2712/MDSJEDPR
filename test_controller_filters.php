<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;

echo "=== Testing ReportController Directly ===\n\n";

$controller = new ReportController();

// Test 1: No filters
echo "Test 1: No filters (All projects)\n";
echo str_repeat("-", 60) . "\n";

$request1 = new Request();
$response1 = $controller->index($request1);
$data1 = $response1->getData();

if (isset($data1['reports'])) {
    $count1 = $data1['reports']->count();
    echo "✓ Total projects: $count1\n";

    foreach ($data1['reports'] as $report) {
        echo "  - PR-{$report->pr_number}: {$report->name}\n";
    }
} else {
    echo "✗ No reports data found\n";
}

echo "\n";

// Test 2: Filter by PR Number = 1
echo "Test 2: Filter by pr_number = 1\n";
echo str_repeat("-", 60) . "\n";

$request2 = new Request(['filter' => ['pr_number' => '1']]);
$response2 = $controller->index($request2);
$data2 = $response2->getData();

if (isset($data2['reports'])) {
    $count2 = $data2['reports']->count();

    if ($count2 == 1) {
        $report = $data2['reports']->first();
        if ($report->pr_number == '1') {
            echo "✓ PASS: Found exactly 1 project with PR = 1\n";
            echo "  ✓ PR Number: {$report->pr_number}\n";
            echo "  ✓ Project Name: {$report->name}\n";
            echo "  ✓ Customer: " . ($report->cust->name ?? 'N/A') . "\n";
            echo "  ✓ PM: " . ($report->ppms->name ?? 'N/A') . "\n";
        } else {
            echo "✗ FAIL: Found 1 result but PR number is '{$report->pr_number}', not '1'\n";
        }
    } else {
        echo "✗ FAIL: Expected 1 result, got $count2\n";
        foreach ($data2['reports'] as $report) {
            echo "  - PR-{$report->pr_number}: {$report->name}\n";
        }
    }
} else {
    echo "✗ No reports data found\n";
}

echo "\n";

// Test 3: Filter by PR Number = 11
echo "Test 3: Filter by pr_number = 11\n";
echo str_repeat("-", 60) . "\n";

$request3 = new Request(['filter' => ['pr_number' => '11']]);
$response3 = $controller->index($request3);
$data3 = $response3->getData();

if (isset($data3['reports'])) {
    $count3 = $data3['reports']->count();

    if ($count3 == 1) {
        $report = $data3['reports']->first();
        if ($report->pr_number == '11') {
            echo "✓ PASS: Found exactly 1 project with PR = 11\n";
            echo "  ✓ PR Number: {$report->pr_number}\n";
            echo "  ✓ Project Name: {$report->name}\n";
            echo "  ✓ Customer: " . ($report->cust->name ?? 'N/A') . "\n";
            echo "  ✓ PM: " . ($report->ppms->name ?? 'N/A') . "\n";
        } else {
            echo "✗ FAIL: Found 1 result but PR number is '{$report->pr_number}', not '11'\n";
        }
    } else {
        echo "✗ FAIL: Expected 1 result, got $count3\n";
        foreach ($data3['reports'] as $report) {
            echo "  - PR-{$report->pr_number}: {$report->name}\n";
        }
    }
}

echo "\n";

// Test 4: Filter by Project Name
echo "Test 4: Filter by name = 'mazen sabry'\n";
echo str_repeat("-", 60) . "\n";

$request4 = new Request(['filter' => ['name' => 'mazen sabry']]);
$response4 = $controller->index($request4);
$data4 = $response4->getData();

if (isset($data4['reports'])) {
    $count4 = $data4['reports']->count();

    if ($count4 == 1) {
        $report = $data4['reports']->first();
        if ($report->name == 'mazen sabry') {
            echo "✓ PASS: Found exactly 1 project with name 'mazen sabry'\n";
            echo "  ✓ PR Number: {$report->pr_number}\n";
            echo "  ✓ Project Name: {$report->name}\n";
        } else {
            echo "✗ FAIL: Found 1 result but name is '{$report->name}'\n";
        }
    } else {
        echo "✗ FAIL: Expected 1 result, got $count4\n";
    }
}

echo "\n";

// Test 5: Filter by Technologies (partial)
echo "Test 5: Filter by technologies (partial) = 'يشسي'\n";
echo str_repeat("-", 60) . "\n";

$request5 = new Request(['filter' => ['technologies' => 'يشسي']]);
$response5 = $controller->index($request5);
$data5 = $response5->getData();

if (isset($data5['reports'])) {
    $count5 = $data5['reports']->count();

    if ($count5 >= 1) {
        echo "✓ PASS: Found $count5 project(s) with 'يشسي' in technologies\n";
        foreach ($data5['reports'] as $report) {
            echo "  ✓ PR-{$report->pr_number}: {$report->name} | Tech: {$report->technologies}\n";
        }
    } else {
        echo "✗ FAIL: Expected at least 1 result, got 0\n";
    }
}

echo "\n";

// Test 6: Invalid filter (should return all)
echo "Test 6: Filter by pr_number = 999 (non-existent)\n";
echo str_repeat("-", 60) . "\n";

$request6 = new Request(['filter' => ['pr_number' => '999']]);
$response6 = $controller->index($request6);
$data6 = $response6->getData();

if (isset($data6['reports'])) {
    $count6 = $data6['reports']->count();

    if ($count6 == 0) {
        echo "✓ PASS: Correctly returned 0 results for non-existent PR\n";
    } else {
        echo "✗ FAIL: Expected 0 results, got $count6\n";
    }
}

echo "\n";
echo str_repeat("=", 60) . "\n";
echo "FILTER TESTS SUMMARY\n";
echo str_repeat("=", 60) . "\n";
echo "\nAll critical filter tests completed!\n";
echo "Check above for any ✗ FAIL markers.\n";
echo "All ✓ PASS means filters are working correctly! 🎉\n";
