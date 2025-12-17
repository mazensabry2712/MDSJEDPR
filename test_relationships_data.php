<?php

echo "
╔═══════════════════════════════════════════════════════════════╗
║     TASKS, RISKS, MILESTONES, INVOICES - DATA CHECK          ║
╚═══════════════════════════════════════════════════════════════╝
";

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n📊 CHECKING ALL TABLES:\n\n";

// 1. Check ptasks table
echo "1️⃣ PTASKS TABLE:\n";
echo str_repeat('─', 70) . "\n";
$tasks = DB::table('ptasks')->select('id', 'pr_number', 'details', 'status')->get();
echo "   Total Tasks: " . $tasks->count() . "\n";
if ($tasks->count() > 0) {
    echo "   Tasks by PR Number:\n";
    foreach ($tasks->groupBy('pr_number') as $prNumber => $prTasks) {
        echo "      PR#{$prNumber}: {$prTasks->count()} task(s)\n";
        foreach ($prTasks as $task) {
            echo "         - ID:{$task->id} | {$task->details} | Status: {$task->status}\n";
        }
    }
} else {
    echo "   ⚠️ NO TASKS FOUND IN DATABASE\n";
}
echo "\n";

// 2. Check risks table
echo "2️⃣ RISKS TABLE:\n";
echo str_repeat('─', 70) . "\n";
$risks = DB::table('risks')->select('id', 'pr_number', 'risk', 'impact', 'status')->get();
echo "   Total Risks: " . $risks->count() . "\n";
if ($risks->count() > 0) {
    echo "   Risks by PR Number:\n";
    foreach ($risks->groupBy('pr_number') as $prNumber => $prRisks) {
        echo "      PR#{$prNumber}: {$prRisks->count()} risk(s)\n";
        foreach ($prRisks as $risk) {
            echo "         - ID:{$risk->id} | {$risk->risk} | Impact: {$risk->impact}\n";
        }
    }
} else {
    echo "   ⚠️ NO RISKS FOUND IN DATABASE\n";
}
echo "\n";

// 3. Check milestones table
echo "3️⃣ MILESTONES TABLE:\n";
echo str_repeat('─', 70) . "\n";
$milestones = DB::table('milestones')->select('id', 'pr_number', 'milestone', 'status')->get();
echo "   Total Milestones: " . $milestones->count() . "\n";
if ($milestones->count() > 0) {
    echo "   Milestones by PR Number:\n";
    foreach ($milestones->groupBy('pr_number') as $prNumber => $prMilestones) {
        echo "      PR#{$prNumber}: {$prMilestones->count()} milestone(s)\n";
        foreach ($prMilestones as $milestone) {
            echo "         - ID:{$milestone->id} | {$milestone->milestone} | Status: {$milestone->status}\n";
        }
    }
} else {
    echo "   ⚠️ NO MILESTONES FOUND IN DATABASE\n";
}
echo "\n";

// 4. Check invoices table
echo "4️⃣ INVOICES TABLE:\n";
echo str_repeat('─', 70) . "\n";
$invoices = DB::table('invoices')->select('id', 'pr_number', 'invoice_number', 'value', 'status')->get();
echo "   Total Invoices: " . $invoices->count() . "\n";
if ($invoices->count() > 0) {
    echo "   Invoices by PR Number:\n";
    foreach ($invoices->groupBy('pr_number') as $prNumber => $prInvoices) {
        echo "      PR#{$prNumber}: {$prInvoices->count()} invoice(s)\n";
        foreach ($prInvoices as $invoice) {
            echo "         - ID:{$invoice->id} | #{$invoice->invoice_number} | Value: {$invoice->value} SAR | Status: {$invoice->status}\n";
        }
    }
} else {
    echo "   ⚠️ NO INVOICES FOUND IN DATABASE\n";
}
echo "\n";

echo str_repeat('═', 70) . "\n\n";

// Now check using Eloquent relationships
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║          CHECKING ELOQUENT RELATIONSHIPS                      ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

use App\Models\Project;

$projects = Project::with(['tasks', 'risks', 'milestones', 'invoices'])->get();

foreach ($projects as $project) {
    echo "PR# {$project->pr_number}: {$project->name}\n";
    echo str_repeat('─', 70) . "\n";

    $tasksCount = $project->tasks->count();
    $risksCount = $project->risks->count();
    $milestonesCount = $project->milestones->count();
    $invoicesCount = $project->invoices->count();

    echo "   Tasks: {$tasksCount}";
    if ($tasksCount > 0) {
        $completed = $project->tasks->whereIn('status', ['Completed', 'completed'])->count();
        echo " ({$completed} completed)";
    }
    echo "\n";

    echo "   Risks: {$risksCount}";
    if ($risksCount > 0) {
        $high = $project->risks->whereIn('impact', ['High', 'high'])->count();
        echo " ({$high} high impact)";
    }
    echo "\n";

    echo "   Milestones: {$milestonesCount}";
    if ($milestonesCount > 0) {
        $done = $project->milestones->whereIn('status', ['Completed', 'completed', 'on track'])->count();
        echo " ({$done} done)";
    }
    echo "\n";

    echo "   Invoices: {$invoicesCount}";
    if ($invoicesCount > 0) {
        $paid = $project->invoices->whereIn('status', ['paid', 'Paid'])->count();
        echo " ({$paid} paid)";
    }
    echo "\n";

    // Check if relationships are loading correctly
    $allEmpty = $tasksCount === 0 && $risksCount === 0 && $milestonesCount === 0 && $invoicesCount === 0;

    if ($allEmpty) {
        echo "   ⚠️ Status: No related data\n";
    } else {
        echo "   ✅ Status: Has related data\n";
    }

    echo "\n";
}

echo str_repeat('═', 70) . "\n\n";

// Summary
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║                      SUMMARY                                  ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

$totalTasks = DB::table('ptasks')->count();
$totalRisks = DB::table('risks')->count();
$totalMilestones = DB::table('milestones')->count();
$totalInvoices = DB::table('invoices')->count();

echo "📊 TOTAL DATA IN DATABASE:\n";
echo "   Tasks: {$totalTasks}\n";
echo "   Risks: {$totalRisks}\n";
echo "   Milestones: {$totalMilestones}\n";
echo "   Invoices: {$totalInvoices}\n\n";

$projectsWithTasks = $projects->filter(fn($p) => $p->tasks->count() > 0)->count();
$projectsWithRisks = $projects->filter(fn($p) => $p->risks->count() > 0)->count();
$projectsWithMilestones = $projects->filter(fn($p) => $p->milestones->count() > 0)->count();
$projectsWithInvoices = $projects->filter(fn($p) => $p->invoices->count() > 0)->count();

echo "📈 PROJECTS WITH DATA:\n";
echo "   Projects with Tasks: {$projectsWithTasks} / {$projects->count()}\n";
echo "   Projects with Risks: {$projectsWithRisks} / {$projects->count()}\n";
echo "   Projects with Milestones: {$projectsWithMilestones} / {$projects->count()}\n";
echo "   Projects with Invoices: {$projectsWithInvoices} / {$projects->count()}\n\n";

if ($totalTasks === 0 && $totalRisks === 0 && $totalMilestones === 0 && $totalInvoices === 0) {
    echo "⚠️  WARNING: NO DATA FOUND IN ANY OF THE TABLES!\n";
    echo "   The database tables are empty except for projects.\n";
    echo "   You need to add data to ptasks, risks, milestones, and invoices tables.\n";
} elseif ($projectsWithTasks === 0 && $projectsWithRisks === 0 && $projectsWithMilestones === 0 && $projectsWithInvoices === 0) {
    echo "⚠️  WARNING: Data exists but NOT linked to any projects!\n";
    echo "   Check that pr_number values in related tables match projects.pr_number\n";
} else {
    echo "✅ DATA IS PROPERLY LINKED TO PROJECTS!\n";
    echo "   Relationships are working correctly.\n";
}
