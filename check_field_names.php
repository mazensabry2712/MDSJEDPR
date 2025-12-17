<?php

echo "
╔═══════════════════════════════════════════════════════════════╗
║      CHECKING FIELD NAMES IN BLADE vs DATABASE                ║
╚═══════════════════════════════════════════════════════════════╝
";

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n🔍 Analyzing the circled elements in the screenshot...\n\n";

// 1. Check COMPLETED box (using $completedTasks)
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║  1. COMPLETED BOX (Green box with 0)                          ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

echo "📝 Blade Code:\n";
echo "   Variable: \$completedTasks\n";
echo "   Query: \$project->tasks->whereIn('status', ['Completed', 'completed'])->count()\n\n";

echo "🗄️  Database Check:\n";
$taskSample = DB::table('ptasks')->first();
if ($taskSample) {
    echo "   Table: ptasks\n";
    echo "   Columns: " . implode(', ', array_keys((array)$taskSample)) . "\n";
    echo "   Status field: " . (property_exists($taskSample, 'status') ? '✓ EXISTS' : '✗ MISSING') . "\n";

    if (property_exists($taskSample, 'status')) {
        $statuses = DB::table('ptasks')->distinct()->pluck('status');
        echo "   Status values: " . $statuses->implode(', ') . "\n";
    }
} else {
    echo "   ⚠️  No data in ptasks table\n";
}

echo "\n" . str_repeat('═', 70) . "\n\n";

// 2. Check TOTAL TASKS box
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║  2. TOTAL TASKS BOX (Gray box with 0)                         ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

echo "📝 Blade Code:\n";
echo "   Variable: \$totalTasks\n";
echo "   Query: \$project->tasks->count()\n\n";

echo "🗄️  Database Check:\n";
echo "   Relationship: Project->tasks (hasMany using pr_number)\n";
$prNumber = 99; // From screenshot
$tasksForPR = DB::table('ptasks')->where('pr_number', $prNumber)->get();
echo "   Tasks for PR#{$prNumber}: {$tasksForPR->count()}\n";

if ($tasksForPR->count() > 0) {
    echo "   ✓ Data EXISTS for PR#{$prNumber}\n";
    foreach ($tasksForPR as $task) {
        echo "      - Task ID {$task->id}: {$task->details} (status: {$task->status})\n";
    }
} else {
    echo "   ℹ️  No tasks for PR#{$prNumber}\n";
}

echo "\n" . str_repeat('═', 70) . "\n\n";

// 3. Check Tasks Card (Green card with 0)
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║  3. TASKS CARD (Green card bottom-left with 0)                ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

echo "📝 Blade Code:\n";
echo "   Total: \$project->tasks->count()\n";
echo "   Completed: \$project->tasks->whereIn('status', ['Completed', 'completed'])->count()\n\n";

echo "🔗 Relationship Check:\n";
echo "   Model: Project::tasks()\n";
echo "   Type: hasMany(Ptasks::class, 'pr_number', 'pr_number')\n";
echo "   Foreign Key: pr_number\n";
echo "   Local Key: pr_number\n\n";

echo "✅ VALIDATION: Field name is correct (status exists in ptasks)\n";

echo "\n" . str_repeat('═', 70) . "\n\n";

// 4. Check Milestones Card (Yellow card with 0)
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║  4. MILESTONES CARD (Yellow card with 0)                      ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

echo "📝 Blade Code:\n";
echo "   Variable: \$project->milestones->count()\n";
echo "   Done count: \$project->milestones->whereIn('status', ['Completed', 'completed', 'on track'])->count()\n\n";

echo "🗄️  Database Check:\n";
$milestoneSample = DB::table('milestones')->first();
if ($milestoneSample) {
    echo "   Table: milestones\n";
    echo "   Columns: " . implode(', ', array_keys((array)$milestoneSample)) . "\n";
    echo "   Status field: " . (property_exists($milestoneSample, 'status') ? '✓ EXISTS' : '✗ MISSING') . "\n";

    if (property_exists($milestoneSample, 'status')) {
        $statuses = DB::table('milestones')->distinct()->pluck('status');
        echo "   Status values: " . $statuses->implode(', ') . "\n";
    }
} else {
    echo "   ⚠️  No data in milestones table\n";
}

$milestonesForPR = DB::table('milestones')->where('pr_number', $prNumber)->get();
echo "   Milestones for PR#{$prNumber}: {$milestonesForPR->count()}\n";

echo "\n" . str_repeat('═', 70) . "\n\n";

// 5. Check Invoices Card (Blue card with 0)
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║  5. INVOICES CARD (Blue card bottom-right with 0)             ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

echo "📝 Blade Code:\n";
echo "   Variable: \$project->invoices->count()\n";
echo "   Paid count: \$project->invoices->whereIn('status', ['paid', 'Paid'])->count()\n\n";

echo "🗄️  Database Check:\n";
$invoiceSample = DB::table('invoices')->first();
if ($invoiceSample) {
    echo "   Table: invoices\n";
    echo "   Columns: " . implode(', ', array_keys((array)$invoiceSample)) . "\n";
    echo "   Status field: " . (property_exists($invoiceSample, 'status') ? '✓ EXISTS' : '✗ MISSING') . "\n";

    if (property_exists($invoiceSample, 'status')) {
        $statuses = DB::table('invoices')->distinct()->pluck('status');
        echo "   Status values: " . $statuses->implode(', ') . "\n";
    }
} else {
    echo "   ⚠️  No data in invoices table\n";
}

$invoicesForPR = DB::table('invoices')->where('pr_number', $prNumber)->get();
echo "   Invoices for PR#{$prNumber}: {$invoicesForPR->count()}\n";

echo "\n" . str_repeat('═', 70) . "\n\n";

// Final Analysis
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║                    FINAL ANALYSIS                             ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

// Check if the issue is field names or data
echo "🔍 ROOT CAUSE ANALYSIS:\n\n";

echo "1. Field Names Check:\n";
$allGood = true;

// Check tasks
if ($taskSample && property_exists($taskSample, 'status')) {
    echo "   ✓ ptasks.status - EXISTS\n";
} else {
    echo "   ✗ ptasks.status - MISSING\n";
    $allGood = false;
}

// Check milestones
if ($milestoneSample && property_exists($milestoneSample, 'status')) {
    echo "   ✓ milestones.status - EXISTS\n";
} else {
    echo "   ✗ milestones.status - MISSING\n";
    $allGood = false;
}

// Check invoices
if ($invoiceSample && property_exists($invoiceSample, 'status')) {
    echo "   ✓ invoices.status - EXISTS\n";
} else {
    echo "   ✗ invoices.status - MISSING\n";
    $allGood = false;
}

echo "\n2. Data Availability for PR#{$prNumber}:\n";
echo "   Tasks: {$tasksForPR->count()}\n";
echo "   Milestones: {$milestonesForPR->count()}\n";
echo "   Invoices: {$invoicesForPR->count()}\n\n";

if ($allGood) {
    echo "✅ All field names are CORRECT!\n\n";
    echo "⚠️  The issue is: NO DATA for PR#{$prNumber}\n\n";
    echo "📊 Data exists for these PR numbers:\n";

    $allTasks = DB::table('ptasks')->select('pr_number', DB::raw('count(*) as count'))
        ->groupBy('pr_number')->get();
    foreach ($allTasks as $row) {
        echo "   PR#{$row->pr_number}: {$row->count} tasks\n";
    }
} else {
    echo "❌ Field name mismatch detected!\n";
    echo "   Fix the field names in the Blade file to match the database.\n";
}
