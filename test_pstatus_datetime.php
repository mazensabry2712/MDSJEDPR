<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "🧪 PSTATUS DATETIME TEST\n";
echo "========================================\n\n";

// Get first project and pm
$project = App\Models\Project::first();
$pm = App\Models\ppms::first();

if (!$project || !$pm) {
    echo "❌ Need at least 1 project and 1 PM!\n";
    exit(1);
}

echo "📝 Creating new Pstatus with current datetime...\n";
echo "--------------------------------------\n";

// Create new record with current datetime
$pstatus = App\Models\Pstatus::create([
    'pr_number' => $project->id,
    'date_time' => now(), // Current date AND time
    'pm_name' => $pm->id,
    'status' => 'Test Status',
    'actual_completion' => 50.00,
    'expected_completion' => now()->addDays(7),
    'notes' => 'Test record with datetime',
]);

echo "✅ Created Pstatus ID: {$pstatus->id}\n";
echo "  - date_time (raw): {$pstatus->date_time}\n";
echo "  - date_time (formatted): " . \Carbon\Carbon::parse($pstatus->date_time)->format('d/m/Y H:i:s') . "\n\n";

// Verify from database
$check = DB::table('pstatuses')->find($pstatus->id);
echo "🔍 Verify from database:\n";
echo "  - date_time: {$check->date_time}\n\n";

if (str_contains($check->date_time, '00:00:00')) {
    echo "❌ Still showing 00:00:00 - PROBLEM!\n";
} else {
    echo "✅ Time is saved correctly!\n";
}

echo "\n📋 All Records:\n";
echo "--------------------------------------\n";
$all = DB::table('pstatuses')->get();
foreach ($all as $record) {
    $hasTime = !str_contains($record->date_time, '00:00:00');
    $icon = $hasTime ? '✅' : '❌';
    echo "{$icon} ID: {$record->id} | date_time: {$record->date_time}\n";
}

echo "\n========================================\n";
