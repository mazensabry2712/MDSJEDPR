<?php

echo "
╔═══════════════════════════════════════════════════════════════╗
║          FIX: UPDATE pr_number TO MATCH PROJECTS              ║
╚═══════════════════════════════════════════════════════════════╝
";

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n📊 CURRENT SITUATION:\n";
echo str_repeat('─', 70) . "\n";

// Check existing projects
$projects = DB::table('projects')->pluck('pr_number', 'id')->toArray();
echo "Existing Projects (pr_numbers): " . implode(', ', $projects) . "\n\n";

// Check orphaned data
$orphanedTasks = DB::table('ptasks')->whereNotIn('pr_number', $projects)->count();
$orphanedRisks = DB::table('risks')->whereNotIn('pr_number', $projects)->count();
$orphanedMilestones = DB::table('milestones')->whereNotIn('pr_number', $projects)->count();
$orphanedInvoices = DB::table('invoices')->whereNotIn('pr_number', $projects)->count();

echo "Orphaned Data (not linked to any project):\n";
echo "   Tasks: {$orphanedTasks}\n";
echo "   Risks: {$orphanedRisks}\n";
echo "   Milestones: {$orphanedMilestones}\n";
echo "   Invoices: {$orphanedInvoices}\n\n";

if ($orphanedTasks + $orphanedRisks + $orphanedMilestones + $orphanedInvoices > 0) {
    echo "╔═══════════════════════════════════════════════════════════════╗\n";
    echo "║                  SOLUTION OPTIONS                             ║\n";
    echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

    echo "Option 1: Move all orphaned data to PR#2 (sdasdas)\n";
    echo "Option 2: Move all orphaned data to PR#1 (qqq)\n";
    echo "Option 3: Create missing projects (PR#3, PR#4, PR#5)\n\n";

    echo "APPLYING SOLUTION: Move to existing projects\n";
    echo str_repeat('═', 70) . "\n\n";

    DB::beginTransaction();

    try {
        // Move PR#3 data to PR#34
        echo "1. Moving PR#3 data to PR#34...\n";
        $tasks3 = DB::table('ptasks')->where('pr_number', 3)->update(['pr_number' => 34]);
        $risks3 = DB::table('risks')->where('pr_number', 3)->update(['pr_number' => 34]);
        $milestones3 = DB::table('milestones')->where('pr_number', 3)->update(['pr_number' => 34]);
        echo "   → Tasks: {$tasks3}, Risks: {$risks3}, Milestones: {$milestones3}\n\n";

        // Move PR#4 data to PR#432
        echo "2. Moving PR#4 data to PR#432...\n";
        $tasks4 = DB::table('ptasks')->where('pr_number', 4)->update(['pr_number' => 432]);
        $risks4 = DB::table('risks')->where('pr_number', 4)->update(['pr_number' => 432]);
        $milestones4 = DB::table('milestones')->where('pr_number', 4)->update(['pr_number' => 432]);
        echo "   → Tasks: {$tasks4}, Risks: {$risks4}, Milestones: {$milestones4}\n\n";

        // Move PR#5 data to PR#99
        echo "3. Moving PR#5 data to PR#99...\n";
        $tasks5 = DB::table('ptasks')->where('pr_number', 5)->update(['pr_number' => 99]);
        $risks5 = DB::table('risks')->where('pr_number', 5)->update(['pr_number' => 99]);
        $milestones5 = DB::table('milestones')->where('pr_number', 5)->update(['pr_number' => 99]);
        echo "   → Tasks: {$tasks5}, Risks: {$risks5}, Milestones: {$milestones5}\n\n";

        DB::commit();

        echo "✅ ALL DATA UPDATED SUCCESSFULLY!\n\n";

    } catch (\Exception $e) {
        DB::rollBack();
        echo "❌ ERROR: " . $e->getMessage() . "\n\n";
        exit(1);
    }

    // Verify the fix
    echo "╔═══════════════════════════════════════════════════════════════╗\n";
    echo "║                    VERIFICATION                               ║\n";
    echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

    $projects = \App\Models\Project::with(['tasks', 'risks', 'milestones', 'invoices'])->get();

    foreach ($projects as $project) {
        $tasksCount = $project->tasks->count();
        $risksCount = $project->risks->count();
        $milestonesCount = $project->milestones->count();
        $invoicesCount = $project->invoices->count();

        $totalData = $tasksCount + $risksCount + $milestonesCount + $invoicesCount;

        if ($totalData > 0) {
            echo "✅ PR# {$project->pr_number}: {$project->name}\n";
            echo "   Tasks: {$tasksCount}, Risks: {$risksCount}, Milestones: {$milestonesCount}, Invoices: {$invoicesCount}\n\n";
        }
    }

    $orphanedAfter = DB::table('ptasks')->whereNotIn('pr_number', $projects->pluck('pr_number')->toArray())->count() +
                     DB::table('risks')->whereNotIn('pr_number', $projects->pluck('pr_number')->toArray())->count() +
                     DB::table('milestones')->whereNotIn('pr_number', $projects->pluck('pr_number')->toArray())->count() +
                     DB::table('invoices')->whereNotIn('pr_number', $projects->pluck('pr_number')->toArray())->count();

    if ($orphanedAfter === 0) {
        echo "╔═══════════════════════════════════════════════════════════════╗\n";
        echo "║         🎉 ALL DATA IS NOW PROPERLY LINKED! 🎉               ║\n";
        echo "╚═══════════════════════════════════════════════════════════════╝\n";
    } else {
        echo "⚠️ Still {$orphanedAfter} orphaned records\n";
    }

} else {
    echo "✅ NO ORPHANED DATA - ALL DATA IS PROPERLY LINKED!\n";
}
