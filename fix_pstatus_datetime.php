<?php

/**
 * Fix PStatus DateTime - Add Time Component
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Pstatus;
use Illuminate\Support\Facades\DB;

echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║     🔧 FIX PSTATUS DATE_TIME - ADD TIME COMPONENT            ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

// Check current column type
echo "📋 Checking column type...\n";
$column = DB::select("SHOW COLUMNS FROM pstatuses WHERE Field = 'date_time'");
if ($column) {
    echo "   Current Type: " . $column[0]->Type . "\n\n";
}

// Get all records
$records = Pstatus::all();
echo "📊 Total Records: " . $records->count() . "\n\n";

if ($records->count() === 0) {
    echo "⚠️  No records to update\n";
    exit;
}

echo "🔄 Updating records with current time...\n\n";

$updated = 0;
foreach ($records as $record) {
    $oldValue = $record->date_time;

    // إذا كان التاريخ موجود بدون وقت، نضيف الوقت الحالي
    if ($oldValue) {
        // تحويل التاريخ إلى datetime مع إضافة الوقت
        $newValue = \Carbon\Carbon::parse($oldValue)->format('Y-m-d H:i:s');

        // إذا كان الوقت 00:00:00، نضيف الوقت الحالي
        if (strpos($newValue, '00:00:00') !== false) {
            $currentTime = now()->format('H:i:s');
            $newValue = \Carbon\Carbon::parse($oldValue)->format('Y-m-d') . ' ' . $currentTime;
        }

        $record->date_time = $newValue;
        $record->save();

        echo "   ✅ Record #{$record->id}:\n";
        echo "      Old: {$oldValue}\n";
        echo "      New: {$newValue}\n\n";

        $updated++;
    }
}

echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║  ✅ Updated {$updated} records successfully!                      ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

// Verify changes
echo "📋 Verification:\n";
$verifyRecords = Pstatus::take(5)->get();
foreach ($verifyRecords as $record) {
    echo "   Record #{$record->id}: {$record->date_time}\n";
}

echo "\n✨ Done! Now date_time will show both date and time.\n";
