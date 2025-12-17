<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "🕐 PSTATUS DATE/TIME FIX CHECK\n";
echo "========================================\n\n";

// Check table structure
echo "📋 Current Table Structure:\n";
echo "--------------------------------------\n";
$columns = DB::select('DESCRIBE pstatuses');
foreach ($columns as $col) {
    if ($col->Field === 'date_time') {
        echo "  ✓ date_time: {$col->Type}\n";
        echo "  ✓ Null: {$col->Null}\n";
        echo "  ✓ Default: {$col->Default}\n\n";

        if (stripos($col->Type, 'datetime') !== false) {
            echo "  ✅ Column type is DATETIME - CORRECT!\n\n";
        } elseif (stripos($col->Type, 'date') !== false) {
            echo "  ❌ Column type is DATE - WRONG! (no time stored)\n";
            echo "  🔧 Need to run migration!\n\n";
        }
    }
}

// Check migrations
echo "📋 Migration Status:\n";
echo "--------------------------------------\n";
$migrations = DB::table('migrations')->where('migration', 'like', '%pstatus%')->get();
foreach ($migrations as $mig) {
    echo "  ✓ {$mig->migration} (batch: {$mig->batch})\n";
}

echo "\n📊 Sample Data:\n";
echo "--------------------------------------\n";
$records = DB::table('pstatuses')->limit(3)->get();

if ($records->isEmpty()) {
    echo "ℹ️  No records in pstatuses table\n\n";
} else {
    foreach ($records as $record) {
        echo "Record ID: {$record->id}\n";
        echo "  - date_time: {$record->date_time}\n";
        echo "  - created_at: {$record->created_at}\n";
        echo "  - updated_at: {$record->updated_at}\n\n";
    }
}

echo "========================================\n";
