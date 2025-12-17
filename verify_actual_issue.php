<?php

echo "
╔═══════════════════════════════════════════════════════════════╗
║          VERIFY ACTUAL ISSUE - WHY CARDS SHOW 0?              ║
╚═══════════════════════════════════════════════════════════════╝
";

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;
use Illuminate\Support\Facades\DB;

// Test PR# 99 specifically (from the screenshot)
echo "\n🔍 TESTING PR# 99 (From Screenshot)\n";
echo str_repeat('═', 70) . "\n\n";

// Method 1: Using Eloquent with relationships
echo "METHOD 1: Eloquent with Relationships\n";
echo "--------------------------------------\n";
$project = Project::with(['tasks', 'risks', 'milestones', 'invoices'])
    ->where('pr_number', 99)
    ->first();

if ($project) {
    echo "✓ Project Found: {$project->name}\n";
    echo "  ID: {$project->id}\n";
    echo "  PR Number: {$project->pr_number}\n\n";

    echo "📊 Loaded Relationships:\n";
    echo "  Tasks: {$project->tasks->count()}\n";
    echo "  Risks: {$project->risks->count()}\n";
    echo "  Milestones: {$project->milestones->count()}\n";
    echo "  Invoices: {$project->invoices->count()}\n\n";

    if ($project->tasks->count() > 0) {
        echo "  Task Details:\n";
        foreach ($project->tasks as $task) {
            echo "    - {$task->details} ({$task->status})\n";
        }
    }
} else {
    echo "❌ Project PR# 99 NOT FOUND!\n";
}

echo "\n" . str_repeat('═', 70) . "\n\n";

// Method 2: Raw Database Query
echo "METHOD 2: Raw Database Queries\n";
echo "-------------------------------\n";

$projectRaw = DB::table('projects')->where('pr_number', 99)->first();
if ($projectRaw) {
    echo "✓ Project exists in database\n";
    echo "  ID: {$projectRaw->id}\n";
    echo "  PR Number: {$projectRaw->pr_number}\n";
    echo "  Name: {$projectRaw->name}\n\n";

    // Check raw data in related tables
    $tasksCount = DB::table('ptasks')->where('pr_number', 99)->count();
    $risksCount = DB::table('risks')->where('pr_number', 99)->count();
    $milestonesCount = DB::table('milestones')->where('pr_number', 99)->count();
    $invoicesCount = DB::table('invoices')->where('pr_number', 99)->count();

    echo "📊 Raw Data Counts:\n";
    echo "  ptasks table: {$tasksCount} records\n";
    echo "  risks table: {$risksCount} records\n";
    echo "  milestones table: {$milestonesCount} records\n";
    echo "  invoices table: {$invoicesCount} records\n\n";

    if ($tasksCount > 0) {
        echo "  Tasks in database:\n";
        $tasks = DB::table('ptasks')->where('pr_number', 99)->get();
        foreach ($tasks as $task) {
            echo "    - ID: {$task->id}, Details: {$task->details}, Status: {$task->status}\n";
        }
        echo "\n";
    }

    if ($risksCount > 0) {
        echo "  Risks in database:\n";
        $risks = DB::table('risks')->where('pr_number', 99)->get();
        foreach ($risks as $risk) {
            echo "    - ID: {$risk->id}, Risk: {$risk->risk}, Impact: {$risk->impact}\n";
        }
        echo "\n";
    }

    if ($milestonesCount > 0) {
        echo "  Milestones in database:\n";
        $milestones = DB::table('milestones')->where('pr_number', 99)->get();
        foreach ($milestones as $milestone) {
            echo "    - ID: {$milestone->id}, Milestone: {$milestone->milestone}, Status: {$milestone->status}\n";
        }
        echo "\n";
    }

    if ($invoicesCount > 0) {
        echo "  Invoices in database:\n";
        $invoices = DB::table('invoices')->where('pr_number', 99)->get();
        foreach ($invoices as $invoice) {
            echo "    - ID: {$invoice->id}, Invoice #: {$invoice->invoice_number}, Value: {$invoice->value}, Status: {$invoice->status}\n";
        }
        echo "\n";
    }
}

echo str_repeat('═', 70) . "\n\n";

// Method 3: Check ALL PR numbers with data
echo "METHOD 3: Check ALL PR Numbers in Each Table\n";
echo "---------------------------------------------\n";

echo "📋 PR Numbers in projects table:\n";
$projectPRs = DB::table('projects')->pluck('pr_number')->toArray();
echo "  " . implode(', ', $projectPRs) . "\n\n";

echo "📝 PR Numbers in ptasks table:\n";
$taskPRs = DB::table('ptasks')->distinct()->pluck('pr_number')->toArray();
echo "  " . implode(', ', $taskPRs) . "\n";
$tasksPerPR = DB::table('ptasks')->select('pr_number', DB::raw('count(*) as count'))
    ->groupBy('pr_number')->get();
foreach ($tasksPerPR as $row) {
    echo "    PR# {$row->pr_number}: {$row->count} tasks\n";
}

echo "\n🔴 PR Numbers in risks table:\n";
$riskPRs = DB::table('risks')->distinct()->pluck('pr_number')->toArray();
if (count($riskPRs) > 0) {
    echo "  " . implode(', ', $riskPRs) . "\n";
    $risksPerPR = DB::table('risks')->select('pr_number', DB::raw('count(*) as count'))
        ->groupBy('pr_number')->get();
    foreach ($risksPerPR as $row) {
        echo "    PR# {$row->pr_number}: {$row->count} risks\n";
    }
} else {
    echo "  No risks data\n";
}

echo "\n🟡 PR Numbers in milestones table:\n";
$milestonePRs = DB::table('milestones')->distinct()->pluck('pr_number')->toArray();
if (count($milestonePRs) > 0) {
    echo "  " . implode(', ', $milestonePRs) . "\n";
    $milestonesPerPR = DB::table('milestones')->select('pr_number', DB::raw('count(*) as count'))
        ->groupBy('pr_number')->get();
    foreach ($milestonesPerPR as $row) {
        echo "    PR# {$row->pr_number}: {$row->count} milestones\n";
    }
} else {
    echo "  No milestones data\n";
}

echo "\n🔵 PR Numbers in invoices table:\n";
$invoicePRs = DB::table('invoices')->distinct()->pluck('pr_number')->toArray();
if (count($invoicePRs) > 0) {
    echo "  " . implode(', ', $invoicePRs) . "\n";
    $invoicesPerPR = DB::table('invoices')->select('pr_number', DB::raw('count(*) as count'))
        ->groupBy('pr_number')->get();
    foreach ($invoicesPerPR as $row) {
        echo "    PR# {$row->pr_number}: {$row->count} invoices\n";
    }
} else {
    echo "  No invoices data\n";
}

echo "\n" . str_repeat('═', 70) . "\n\n";

// Method 4: Check Project Model Relationships
echo "METHOD 4: Verify Project Model Relationships\n";
echo "---------------------------------------------\n";

$projectModel = new Project();
echo "📋 Checking Project model relationships...\n\n";

// Get the relationship definition
echo "Checking 'tasks' relationship:\n";
try {
    $relation = $projectModel->tasks();
    echo "  ✓ Relationship exists\n";
    echo "  Type: " . get_class($relation) . "\n";
    echo "  Foreign Key: " . $relation->getForeignKeyName() . "\n";
    echo "  Local Key: " . $relation->getLocalKeyName() . "\n";
} catch (\Exception $e) {
    echo "  ❌ Error: " . $e->getMessage() . "\n";
}

echo "\nChecking 'risks' relationship:\n";
try {
    $relation = $projectModel->risks();
    echo "  ✓ Relationship exists\n";
    echo "  Type: " . get_class($relation) . "\n";
    echo "  Foreign Key: " . $relation->getForeignKeyName() . "\n";
    echo "  Local Key: " . $relation->getLocalKeyName() . "\n";
} catch (\Exception $e) {
    echo "  ❌ Error: " . $e->getMessage() . "\n";
}

echo "\nChecking 'milestones' relationship:\n";
try {
    $relation = $projectModel->milestones();
    echo "  ✓ Relationship exists\n";
    echo "  Type: " . get_class($relation) . "\n";
    echo "  Foreign Key: " . $relation->getForeignKeyName() . "\n";
    echo "  Local Key: " . $relation->getLocalKeyName() . "\n";
} catch (\Exception $e) {
    echo "  ❌ Error: " . $e->getMessage() . "\n";
}

echo "\nChecking 'invoices' relationship:\n";
try {
    $relation = $projectModel->invoices();
    echo "  ✓ Relationship exists\n";
    echo "  Type: " . get_class($relation) . "\n";
    echo "  Foreign Key: " . $relation->getForeignKeyName() . "\n";
    echo "  Local Key: " . $relation->getLocalKeyName() . "\n";
} catch (\Exception $e) {
    echo "  ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat('═', 70) . "\n\n";

// Final Analysis
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║                    FINAL ANALYSIS                             ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

$allMatch = true;
$issues = [];

// Compare project PRs with data PRs
foreach (['tasks' => $taskPRs, 'risks' => $riskPRs, 'milestones' => $milestonePRs, 'invoices' => $invoicePRs] as $type => $dataPRs) {
    $orphans = array_diff($dataPRs, $projectPRs);
    if (count($orphans) > 0) {
        $allMatch = false;
        $issues[] = "❌ {$type}: Orphan data for PR# " . implode(', ', $orphans);
    }
}

if ($allMatch && count($taskPRs) == 0 && count($riskPRs) == 0 && count($milestonePRs) == 0) {
    echo "⚠️  ISSUE: NO DATA EXISTS AT ALL\n";
    echo "   The cards show 0 because there is literally no data in the database.\n";
    echo "   All ptasks, risks, milestones tables are empty.\n\n";
} elseif ($allMatch) {
    echo "✅ ALL DATA MATCHES CORRECTLY\n";
    echo "   All data in ptasks, risks, milestones, invoices\n";
    echo "   has corresponding projects.\n\n";
} else {
    echo "🔴 ORPHAN DATA DETECTED!\n\n";
    foreach ($issues as $issue) {
        echo "   {$issue}\n";
    }
    echo "\n";
}

echo "📊 SUMMARY:\n";
echo "   Projects: " . count($projectPRs) . " (" . implode(', ', $projectPRs) . ")\n";
echo "   Tasks data for PR#: " . (count($taskPRs) > 0 ? implode(', ', $taskPRs) : 'NONE') . "\n";
echo "   Risks data for PR#: " . (count($riskPRs) > 0 ? implode(', ', $riskPRs) : 'NONE') . "\n";
echo "   Milestones data for PR#: " . (count($milestonePRs) > 0 ? implode(', ', $milestonePRs) : 'NONE') . "\n";
echo "   Invoices data for PR#: " . (count($invoicePRs) > 0 ? implode(', ', $invoicePRs) : 'NONE') . "\n\n";

echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║              ✅ VERIFICATION COMPLETE                         ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n";
