<?php

echo "
╔═══════════════════════════════════════════════════════════════╗
║     COMPLETE VERIFICATION - Models, Controllers, Migrations   ║
╚═══════════════════════════════════════════════════════════════╝
";

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n🔍 STEP 1: CHECK DATABASE SCHEMA\n";
echo str_repeat('═', 70) . "\n\n";

// Get actual column types and foreign keys
$tables = ['ptasks', 'risks', 'milestones', 'invoices'];

foreach ($tables as $table) {
    echo "📋 Table: {$table}\n";
    echo "   Columns:\n";

    $columns = DB::select("SHOW COLUMNS FROM {$table}");
    foreach ($columns as $column) {
        if ($column->Field == 'pr_number' || $column->Field == 'id') {
            echo "      - {$column->Field}: {$column->Type} (Key: {$column->Key})\n";
        }
    }

    echo "   Foreign Keys:\n";
    $foreignKeys = DB::select("
        SELECT
            CONSTRAINT_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = '{$table}'
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");

    if (count($foreignKeys) > 0) {
        foreach ($foreignKeys as $fk) {
            echo "      ✓ {$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}\n";
        }
    } else {
        echo "      ⚠️  No foreign keys found!\n";
    }
    echo "\n";
}

echo str_repeat('═', 70) . "\n\n";

echo "🔍 STEP 2: CHECK PROJECT TABLE STRUCTURE\n";
echo str_repeat('═', 70) . "\n\n";

echo "📋 Table: projects\n";
echo "   Key Columns:\n";
$projectColumns = DB::select("SHOW COLUMNS FROM projects WHERE Field IN ('id', 'pr_number')");
foreach ($projectColumns as $column) {
    echo "      - {$column->Field}: {$column->Type} (Key: {$column->Key})\n";
}
echo "\n";

echo "   Sample Data:\n";
$projects = DB::table('projects')->select('id', 'pr_number', 'name')->get();
foreach ($projects as $project) {
    echo "      ID: {$project->id}, PR#: {$project->pr_number}, Name: {$project->name}\n";
}

echo "\n" . str_repeat('═', 70) . "\n\n";

echo "🔍 STEP 3: CHECK ACTUAL DATA RELATIONSHIPS\n";
echo str_repeat('═', 70) . "\n\n";

// Check what pr_number values exist in each table
echo "📊 PR_NUMBER values in each table:\n\n";

foreach ($tables as $table) {
    echo "Table: {$table}\n";
    $data = DB::table($table)->select('id', 'pr_number')->get();
    if ($data->count() > 0) {
        foreach ($data as $row) {
            // Check if pr_number matches project.id or project.pr_number
            $matchById = DB::table('projects')->where('id', $row->pr_number)->first();
            $matchByPrNumber = DB::table('projects')->where('pr_number', $row->pr_number)->first();

            $status = '';
            if ($matchById) {
                $status = "✅ Matches project.id={$matchById->id} (PR#{$matchById->pr_number})";
            } elseif ($matchByPrNumber) {
                $status = "❓ Matches project.pr_number={$matchByPrNumber->pr_number} (id={$matchByPrNumber->id})";
            } else {
                $status = "❌ NO MATCH (orphan data)";
            }

            echo "   Row ID: {$row->id}, pr_number: {$row->pr_number} -> {$status}\n";
        }
    } else {
        echo "   (empty)\n";
    }
    echo "\n";
}

echo str_repeat('═', 70) . "\n\n";

echo "🔍 STEP 4: TEST ELOQUENT RELATIONSHIPS\n";
echo str_repeat('═', 70) . "\n\n";

use App\Models\Project;

echo "Testing Project relationships:\n\n";

$testProject = Project::where('pr_number', 2)->first(); // PR#2 has tasks
if ($testProject) {
    echo "✓ Found Project: PR#{$testProject->pr_number} (id={$testProject->id})\n";
    echo "  Name: {$testProject->name}\n\n";

    // Test tasks relationship
    echo "  Testing tasks relationship:\n";
    $tasks = $testProject->tasks;
    echo "    Loaded: {$tasks->count()} tasks\n";

    // Check raw data
    $rawTasks = DB::table('ptasks')->where('pr_number', $testProject->id)->get();
    echo "    Raw query (pr_number = project.id): {$rawTasks->count()} tasks\n";

    $rawTasks2 = DB::table('ptasks')->where('pr_number', $testProject->pr_number)->get();
    echo "    Raw query (pr_number = project.pr_number): {$rawTasks2->count()} tasks\n\n";

    // Test risks
    echo "  Testing risks relationship:\n";
    $risks = $testProject->risks;
    echo "    Loaded: {$risks->count()} risks\n";
    $rawRisks = DB::table('risks')->where('pr_number', $testProject->id)->get();
    echo "    Raw query (pr_number = project.id): {$rawRisks->count()} risks\n";
    $rawRisks2 = DB::table('risks')->where('pr_number', $testProject->pr_number)->get();
    echo "    Raw query (pr_number = project.pr_number): {$rawRisks2->count()} risks\n\n";
}

echo str_repeat('═', 70) . "\n\n";

echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║                    DIAGNOSIS RESULT                           ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

// Check the actual problem
$problem = "";
$solution = "";

// Get sample data
$sampleTask = DB::table('ptasks')->first();
if ($sampleTask) {
    $projectById = DB::table('projects')->where('id', $sampleTask->pr_number)->first();
    $projectByPrNumber = DB::table('projects')->where('pr_number', $sampleTask->pr_number)->first();

    if ($projectById) {
        echo "✅ FOREIGN KEY WORKING CORRECTLY\n";
        echo "   ptasks.pr_number ({$sampleTask->pr_number}) -> projects.id\n";
        echo "   This is the CORRECT setup (foreign key references id)\n\n";

        echo "❓ BUT: Project Model uses wrong keys?\n";
        echo "   Check: Does Project model use 'pr_number' as both foreign and local key?\n";
        echo "   This would be WRONG if foreign key actually references 'id'\n\n";

        $problem = "MODEL_MISMATCH";
        $solution = "Models define hasMany with ('pr_number', 'pr_number') but should be ('pr_number', 'id')";
    } elseif ($projectByPrNumber) {
        echo "❌ DATA MISMATCH\n";
        echo "   ptasks.pr_number ({$sampleTask->pr_number}) matches projects.pr_number\n";
        echo "   BUT foreign key expects projects.id\n\n";

        $problem = "DATA_MISMATCH";
        $solution = "Data stored with pr_number values but foreign key expects id values";
    } else {
        echo "❌ ORPHAN DATA\n";
        echo "   ptasks.pr_number ({$sampleTask->pr_number}) has no matching project\n\n";

        $problem = "ORPHAN";
        $solution = "Data exists for non-existent projects";
    }
}

echo "🔴 PROBLEM: {$problem}\n";
echo "💡 SOLUTION: {$solution}\n\n";

echo "📋 RECOMMENDED ACTION:\n";
if ($problem == "MODEL_MISMATCH") {
    echo "   Fix Project model relationships:\n";
    echo "   Change: hasMany(Ptasks::class, 'pr_number', 'pr_number')\n";
    echo "   To: hasMany(Ptasks::class, 'pr_number', 'id')\n";
    echo "   \n";
    echo "   Do same for tasks, risks, milestones, invoices relationships\n";
} elseif ($problem == "DATA_MISMATCH") {
    echo "   Update data in ptasks, risks, milestones, invoices:\n";
    echo "   Change pr_number values to match project.id instead of project.pr_number\n";
} else {
    echo "   Create missing projects or delete orphan data\n";
}

echo "\n╔═══════════════════════════════════════════════════════════════╗\n";
echo "║              ✅ COMPLETE VERIFICATION DONE                    ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n";
