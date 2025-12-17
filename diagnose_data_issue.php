<?php

echo "
╔═══════════════════════════════════════════════════════════════╗
║     WHY DATA IS NOT SHOWING? - ROOT CAUSE ANALYSIS           ║
╚═══════════════════════════════════════════════════════════════╝
";

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;

echo "\n🔍 Investigating why data is not showing for projects...\n\n";

// Check all projects and their relationships
$projects = Project::with(['tasks', 'risks', 'milestones', 'invoices'])->get();

echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║  STEP 1: Check Projects in Database                          ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

echo "Total Projects: {$projects->count()}\n\n";

foreach ($projects as $project) {
    echo "PR# {$project->pr_number}: {$project->name}\n";
    echo "   ID: {$project->id}\n";
    echo "   Tasks loaded via relationship: {$project->tasks->count()}\n";
    echo "   Risks loaded via relationship: {$project->risks->count()}\n";
    echo "   Milestones loaded via relationship: {$project->milestones->count()}\n";
    echo "   Invoices loaded via relationship: {$project->invoices->count()}\n";
    echo "\n";
}

echo str_repeat('═', 70) . "\n\n";

// Check raw data in related tables
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║  STEP 2: Check Raw Data in Related Tables                    ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

// Tasks
echo "📋 PTASKS TABLE:\n";
$allTasks = DB::table('ptasks')->get();
echo "   Total records: {$allTasks->count()}\n";
if ($allTasks->count() > 0) {
    echo "   pr_number distribution:\n";
    $taskGroups = $allTasks->groupBy('pr_number');
    foreach ($taskGroups as $prNum => $tasks) {
        echo "      PR# {$prNum}: {$tasks->count()} tasks\n";
        foreach ($tasks as $task) {
            echo "         - ID {$task->id}: {$task->details} (status: {$task->status})\n";
        }
    }
}
echo "\n";

// Risks
echo "⚠️  RISKS TABLE:\n";
$allRisks = DB::table('risks')->get();
echo "   Total records: {$allRisks->count()}\n";
if ($allRisks->count() > 0) {
    echo "   pr_number distribution:\n";
    $riskGroups = $allRisks->groupBy('pr_number');
    foreach ($riskGroups as $prNum => $risks) {
        echo "      PR# {$prNum}: {$risks->count()} risks\n";
    }
}
echo "\n";

// Milestones
echo "🎯 MILESTONES TABLE:\n";
$allMilestones = DB::table('milestones')->get();
echo "   Total records: {$allMilestones->count()}\n";
if ($allMilestones->count() > 0) {
    echo "   pr_number distribution:\n";
    $milestoneGroups = $allMilestones->groupBy('pr_number');
    foreach ($milestoneGroups as $prNum => $milestones) {
        echo "      PR# {$prNum}: {$milestones->count()} milestones\n";
    }
}
echo "\n";

// Invoices
echo "💰 INVOICES TABLE:\n";
$allInvoices = DB::table('invoices')->get();
echo "   Total records: {$allInvoices->count()}\n";
if ($allInvoices->count() > 0) {
    echo "   pr_number distribution:\n";
    $invoiceGroups = $allInvoices->groupBy('pr_number');
    foreach ($invoiceGroups as $prNum => $invoices) {
        echo "      PR# {$prNum}: {$invoices->count()} invoices\n";
    }
}

echo "\n" . str_repeat('═', 70) . "\n\n";

// Check for mismatch
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║  STEP 3: Find the Mismatch - Why Data Not Showing?           ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

$projectPRNumbers = $projects->pluck('pr_number')->toArray();
$taskPRNumbers = $allTasks->pluck('pr_number')->unique()->toArray();
$riskPRNumbers = $allRisks->pluck('pr_number')->unique()->toArray();
$milestonePRNumbers = $allMilestones->pluck('pr_number')->unique()->toArray();
$invoicePRNumbers = $allInvoices->pluck('pr_number')->unique()->toArray();

echo "🔍 ANALYSIS:\n\n";

echo "1️⃣  Projects in Database:\n";
echo "   PR Numbers: " . implode(', ', $projectPRNumbers) . "\n\n";

echo "2️⃣  Tasks Data Available For:\n";
echo "   PR Numbers: " . implode(', ', $taskPRNumbers) . "\n";
$orphanTasks = array_diff($taskPRNumbers, $projectPRNumbers);
if (!empty($orphanTasks)) {
    echo "   ⚠️  ORPHAN DATA (tasks without projects): PR# " . implode(', ', $orphanTasks) . "\n";
}
echo "\n";

echo "3️⃣  Risks Data Available For:\n";
echo "   PR Numbers: " . implode(', ', $riskPRNumbers) . "\n";
$orphanRisks = array_diff($riskPRNumbers, $projectPRNumbers);
if (!empty($orphanRisks)) {
    echo "   ⚠️  ORPHAN DATA (risks without projects): PR# " . implode(', ', $orphanRisks) . "\n";
}
echo "\n";

echo "4️⃣  Milestones Data Available For:\n";
echo "   PR Numbers: " . implode(', ', $milestonePRNumbers) . "\n";
$orphanMilestones = array_diff($milestonePRNumbers, $projectPRNumbers);
if (!empty($orphanMilestones)) {
    echo "   ⚠️  ORPHAN DATA (milestones without projects): PR# " . implode(', ', $orphanMilestones) . "\n";
}
echo "\n";

echo "5️⃣  Invoices Data Available For:\n";
echo "   PR Numbers: " . implode(', ', $invoicePRNumbers) . "\n";
$orphanInvoices = array_diff($invoicePRNumbers, $projectPRNumbers);
if (!empty($orphanInvoices)) {
    echo "   ⚠️  ORPHAN DATA (invoices without projects): PR# " . implode(', ', $orphanInvoices) . "\n";
}

echo "\n" . str_repeat('═', 70) . "\n\n";

// Conclusion
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║                      CONCLUSION                               ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

$hasOrphans = !empty($orphanTasks) || !empty($orphanRisks) || !empty($orphanMilestones) || !empty($orphanInvoices);

if ($hasOrphans) {
    echo "🔴 PROBLEM FOUND: ORPHAN DATA!\n\n";
    echo "📊 Summary:\n";

    if (!empty($orphanTasks)) {
        echo "   ❌ Tasks exist for PR# " . implode(', ', $orphanTasks) . " but these projects DON'T EXIST\n";
    }
    if (!empty($orphanRisks)) {
        echo "   ❌ Risks exist for PR# " . implode(', ', $orphanRisks) . " but these projects DON'T EXIST\n";
    }
    if (!empty($orphanMilestones)) {
        echo "   ❌ Milestones exist for PR# " . implode(', ', $orphanMilestones) . " but these projects DON'T EXIST\n";
    }
    if (!empty($orphanInvoices)) {
        echo "   ❌ Invoices exist for PR# " . implode(', ', $orphanInvoices) . " but these projects DON'T EXIST\n";
    }

    echo "\n💡 SOLUTIONS:\n\n";
    echo "   Option 1: Create missing projects\n";
    echo "   -------------------------------------\n";
    foreach (array_unique(array_merge($orphanTasks, $orphanRisks, $orphanMilestones, $orphanInvoices)) as $orphanPR) {
        echo "   - Create Project with PR# {$orphanPR}\n";
    }

    echo "\n   Option 2: Update orphan data to match existing projects\n";
    echo "   --------------------------------------------------------\n";
    echo "   - Update tasks/risks/milestones/invoices pr_number to match existing projects\n";
    echo "   - Available projects: PR# " . implode(', ', $projectPRNumbers) . "\n";

    echo "\n   Option 3: Delete orphan data\n";
    echo "   ----------------------------\n";
    echo "   - Delete tasks/risks/milestones/invoices that don't belong to any project\n";

} else {
    echo "✅ No orphan data found!\n\n";
    echo "📊 Data Distribution:\n";

    foreach ($projects as $project) {
        $hasTasks = in_array($project->pr_number, $taskPRNumbers);
        $hasRisks = in_array($project->pr_number, $riskPRNumbers);
        $hasMilestones = in_array($project->pr_number, $milestonePRNumbers);
        $hasInvoices = in_array($project->pr_number, $invoicePRNumbers);

        echo "   PR# {$project->pr_number}: ";

        if (!$hasTasks && !$hasRisks && !$hasMilestones && !$hasInvoices) {
            echo "❌ NO DATA (empty project)\n";
        } else {
            $items = [];
            if ($hasTasks) $items[] = "Tasks";
            if ($hasRisks) $items[] = "Risks";
            if ($hasMilestones) $items[] = "Milestones";
            if ($hasInvoices) $items[] = "Invoices";
            echo "✓ Has " . implode(', ', $items) . "\n";
        }
    }
}

echo "\n╔═══════════════════════════════════════════════════════════════╗\n";
echo "║              DIAGNOSIS COMPLETE                               ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n";
