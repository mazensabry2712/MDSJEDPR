<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

echo "=== Testing Report Filters ===\n\n";

// Test 1: Get all projects
echo "Test 1: All Projects\n";
$allProjects = Project::with(['vendor', 'cust', 'ds', 'aams', 'ppms'])->get();
echo "Total Projects: " . $allProjects->count() . "\n";
foreach ($allProjects as $project) {
    echo "  - PR: {$project->pr_number} | Name: {$project->name}\n";
}
echo "\n";

// Test 2: Filter by PR Number
echo "Test 2: Filter by PR Number = '1'\n";
$filtered = QueryBuilder::for(Project::class)
    ->with(['vendor', 'cust', 'ds', 'aams', 'ppms'])
    ->allowedFilters([AllowedFilter::exact('pr_number')])
    ->get();
echo "Results: " . $filtered->count() . "\n\n";

// Test 3: Get distinct values for dropdowns
echo "Test 3: Dropdown Values\n";
$prNumbers = Project::distinct()->whereNotNull('pr_number')->pluck('pr_number')->filter()->sort()->values();
echo "PR Numbers: " . $prNumbers->implode(', ') . "\n";

$projectNames = Project::distinct()->whereNotNull('name')->pluck('name')->filter()->sort()->values();
echo "Project Names: " . $projectNames->implode(', ') . "\n";

$technologies = Project::distinct()->whereNotNull('technologies')->pluck('technologies')->filter()->sort()->values();
echo "Technologies: " . $technologies->implode(', ') . "\n\n";

// Test 4: Test relationships
echo "Test 4: Relationships\n";
$project = Project::with(['vendor', 'cust', 'ds', 'aams', 'ppms'])->first();
if ($project) {
    echo "Project: {$project->name}\n";
    echo "  - Vendor: " . ($project->vendor->vendors ?? 'N/A') . "\n";
    echo "  - Customer: " . ($project->cust->name ?? 'N/A') . "\n";
    echo "  - DS: " . ($project->ds->dsname ?? 'N/A') . "\n";
    echo "  - PM: " . ($project->ppms->name ?? 'N/A') . "\n";
    echo "  - AM: " . ($project->aams->name ?? 'N/A') . "\n";
}

echo "\n=== Tests Complete ===\n";
