<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Http\Request;

echo "=== Testing QueryBuilder with Laravel Request ===\n\n";

// Create Laravel Request instance
$request = Request::create('/reports', 'GET', ['filter' => ['pr_number' => '1']]);
app()->instance('request', $request);

echo "Request URL: " . $request->fullUrl() . "\n";
echo "Request params: " . json_encode($request->all()) . "\n";
echo "Filter param: " . json_encode($request->input('filter')) . "\n\n";

echo "Test 1: Filter by pr_number = 1\n";
echo str_repeat("-", 60) . "\n";

$reports = QueryBuilder::for(Project::class, $request)
    ->with(['vendor', 'cust', 'ds', 'aams', 'ppms'])
    ->allowedFilters([
        AllowedFilter::callback('pr_number', function ($query, $value) {
            echo "  ✓ Callback executed with value: '{$value}'\n";
            $query->where('pr_number', '=', $value);
        }),
    ])
    ->get();

echo "\nResults: {$reports->count()}\n";
foreach ($reports as $report) {
    echo "  - PR-{$report->pr_number}: {$report->name}\n";
}

if ($reports->count() == 1 && $reports->first()->pr_number == '1') {
    echo "\n✅ SUCCESS: Filter working correctly!\n";
} else {
    echo "\n❌ FAILED: Expected 1 result with PR=1\n";
}

echo "\n";

// Test 2: Filter by pr_number = 11
$request2 = Request::create('/reports', 'GET', ['filter' => ['pr_number' => '11']]);
app()->instance('request', $request2);

echo "Test 2: Filter by pr_number = 11\n";
echo str_repeat("-", 60) . "\n";

$reports2 = QueryBuilder::for(Project::class, $request2)
    ->with(['vendor', 'cust', 'ds', 'aams', 'ppms'])
    ->allowedFilters([
        AllowedFilter::callback('pr_number', function ($query, $value) {
            echo "  ✓ Callback executed with value: '{$value}'\n";
            $query->where('pr_number', '=', $value);
        }),
    ])
    ->get();

echo "\nResults: {$reports2->count()}\n";
foreach ($reports2 as $report) {
    echo "  - PR-{$report->pr_number}: {$report->name}\n";
}

if ($reports2->count() == 1 && $reports2->first()->pr_number == '11') {
    echo "\n✅ SUCCESS: Filter working correctly!\n";
} else {
    echo "\n❌ FAILED: Expected 1 result with PR=11\n";
}

echo "\n";

// Test 3: Filter by name
$request3 = Request::create('/reports', 'GET', ['filter' => ['name' => 'mazen sabry']]);
app()->instance('request', $request3);

echo "Test 3: Filter by name = 'mazen sabry'\n";
echo str_repeat("-", 60) . "\n";

$reports3 = QueryBuilder::for(Project::class, $request3)
    ->with(['vendor', 'cust', 'ds', 'aams', 'ppms'])
    ->allowedFilters([
        AllowedFilter::callback('name', function ($query, $value) {
            echo "  ✓ Callback executed with value: '{$value}'\n";
            $query->where('name', '=', $value);
        }),
    ])
    ->get();

echo "\nResults: {$reports3->count()}\n";
foreach ($reports3 as $report) {
    echo "  - PR-{$report->pr_number}: {$report->name}\n";
}

if ($reports3->count() == 1 && $reports3->first()->name == 'mazen sabry') {
    echo "\n✅ SUCCESS: Filter working correctly!\n";
} else {
    echo "\n❌ FAILED: Expected 1 result with name='mazen sabry'\n";
}

echo "\n";
echo str_repeat("=", 60) . "\n";
echo "FINAL RESULT\n";
echo str_repeat("=", 60) . "\n";
echo "\nIf all tests show ✅ SUCCESS, the filters are working!\n";
