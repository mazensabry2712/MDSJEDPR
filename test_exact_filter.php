<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

echo "=== Testing Exact Filters ===\n\n";

// Simulate query string parameters
$_GET['filter'] = ['pr_number' => '1'];

$reports = QueryBuilder::for(Project::class)
    ->with(['vendor', 'cust', 'ds', 'aams', 'ppms'])
    ->allowedFilters([
        AllowedFilter::exact('pr_number'),
        AllowedFilter::exact('name'),
        AllowedFilter::partial('technologies'),
        AllowedFilter::exact('customer_po'),
    ])
    ->get();

echo "Filter: pr_number = '1'\n";
echo "Results: " . $reports->count() . "\n";
foreach ($reports as $project) {
    echo "  - PR: {$project->pr_number} | Name: {$project->name}\n";
}
echo "\n";

// Test with PR Number 11
unset($_GET);
$_GET['filter'] = ['pr_number' => '11'];

$reports2 = QueryBuilder::for(Project::class)
    ->with(['vendor', 'cust', 'ds', 'aams', 'ppms'])
    ->allowedFilters([
        AllowedFilter::exact('pr_number'),
    ])
    ->get();

echo "Filter: pr_number = '11'\n";
echo "Results: " . $reports2->count() . "\n";
foreach ($reports2 as $project) {
    echo "  - PR: {$project->pr_number} | Name: {$project->name}\n";
}

echo "\n=== Test Complete ===\n";
