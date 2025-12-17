<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;
use Illuminate\Support\Facades\Request;

echo "=== Testing Direct WHERE Clause ===\n\n";

// Test 1: Direct WHERE with pr_number = '1'
$projects1 = Project::where('pr_number', '=', '1')->get();
echo "Direct WHERE pr_number = '1': " . $projects1->count() . " results\n";
foreach ($projects1 as $project) {
    echo "  - PR: {$project->pr_number} | Name: {$project->name}\n";
}
echo "\n";

// Test 2: Direct WHERE with pr_number = '11'
$projects2 = Project::where('pr_number', '=', '11')->get();
echo "Direct WHERE pr_number = '11': " . $projects2->count() . " results\n";
foreach ($projects2 as $project) {
    echo "  - PR: {$project->pr_number} | Name: {$project->name}\n";
}
echo "\n";

// Test 3: Check all projects
$all = Project::all();
echo "Total Projects: " . $all->count() . "\n";
foreach ($all as $project) {
    echo "  - ID: {$project->id} | PR: '{$project->pr_number}' | Name: {$project->name}\n";
}
