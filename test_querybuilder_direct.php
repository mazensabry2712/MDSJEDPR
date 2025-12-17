<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

echo "=== Testing QueryBuilder with Simulated Request ===\n\n";

// Simulate query string
$_SERVER['REQUEST_URI'] = '/reports?filter[pr_number]=1';
$_SERVER['QUERY_STRING'] = 'filter[pr_number]=1';
$_GET = ['filter' => ['pr_number' => '1']];

echo "Request URI: {$_SERVER['REQUEST_URI']}\n";
echo "GET params: " . json_encode($_GET) . "\n\n";

echo "Test 1: Filter by pr_number = 1\n";
echo str_repeat("-", 60) . "\n";

$reports = QueryBuilder::for(Project::class)
    ->with(['vendor', 'cust', 'ds', 'aams', 'ppms'])
    ->allowedFilters([
        AllowedFilter::callback('pr_number', function ($query, $value) {
            echo "  → Callback executed with value: '{$value}'\n";
            $query->where('pr_number', '=', $value);
        }),
    ])
    ->get();

echo "\nResults: {$reports->count()}\n";
foreach ($reports as $report) {
    echo "  - PR-{$report->pr_number}: {$report->name}\n";
}

echo "\n";

// Test 2: Filter by pr_number = 11
unset($_GET);
unset($_SERVER['REQUEST_URI']);
unset($_SERVER['QUERY_STRING']);

$_SERVER['REQUEST_URI'] = '/reports?filter[pr_number]=11';
$_SERVER['QUERY_STRING'] = 'filter[pr_number]=11';
$_GET = ['filter' => ['pr_number' => '11']];

echo "Test 2: Filter by pr_number = 11\n";
echo str_repeat("-", 60) . "\n";
echo "Request URI: {$_SERVER['REQUEST_URI']}\n";
echo "GET params: " . json_encode($_GET) . "\n\n";

$reports2 = QueryBuilder::for(Project::class)
    ->with(['vendor', 'cust', 'ds', 'aams', 'ppms'])
    ->allowedFilters([
        AllowedFilter::callback('pr_number', function ($query, $value) {
            echo "  → Callback executed with value: '{$value}'\n";
            $query->where('pr_number', '=', $value);
        }),
    ])
    ->get();

echo "\nResults: {$reports2->count()}\n";
foreach ($reports2 as $report) {
    echo "  - PR-{$report->pr_number}: {$report->name}\n";
}

echo "\n";

// Test 3: No filters
unset($_GET);
unset($_SERVER['REQUEST_URI']);
unset($_SERVER['QUERY_STRING']);

$_SERVER['REQUEST_URI'] = '/reports';
$_SERVER['QUERY_STRING'] = '';
$_GET = [];

echo "Test 3: No filters (All projects)\n";
echo str_repeat("-", 60) . "\n";

$reports3 = QueryBuilder::for(Project::class)
    ->with(['vendor', 'cust', 'ds', 'aams', 'ppms'])
    ->allowedFilters([
        AllowedFilter::callback('pr_number', function ($query, $value) {
            echo "  → Callback executed (should not see this)\n";
            $query->where('pr_number', '=', $value);
        }),
    ])
    ->get();

echo "\nResults: {$reports3->count()}\n";
foreach ($reports3 as $report) {
    echo "  - PR-{$report->pr_number}: {$report->name}\n";
}

echo "\n";
echo str_repeat("=", 60) . "\n";
echo "Test Complete!\n";
