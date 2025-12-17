<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "🕐 PSTATUS DATE/TIME DIAGNOSTIC\n";
echo "========================================\n\n";

// Check table structure
echo "📋 Table Structure:\n";
echo "--------------------------------------\n";
$columns = DB::select('DESCRIBE pstatus');
foreach ($columns as $col) {
    if (stripos($col->Field, 'date') !== false || stripos($col->Field, 'time') !== false || stripos($col->Field, 'created') !== false || stripos($col->Field, 'updated') !== false) {
        echo "  ✓ {$col->Field}: {$col->Type}\n";
    }
}

echo "\n📊 Sample Data:\n";
echo "--------------------------------------\n";
$records = DB::table('pstatus')->limit(3)->get();

if ($records->isEmpty()) {
    echo "❌ No records found in pstatus table!\n\n";
} else {
    foreach ($records as $record) {
        echo "Record ID: {$record->id}\n";
        foreach ($record as $field => $value) {
            if (stripos($field, 'date') !== false || stripos($field, 'time') !== false || stripos($field, 'created') !== false || stripos($field, 'updated') !== false) {
                echo "  - {$field}: {$value}\n";
            }
        }
        echo "\n";
    }
}

// Check Migration
echo "🔍 Checking Migration File:\n";
echo "--------------------------------------\n";
$migrations = glob(database_path('migrations/*_create_pstatus_table.php'));
if (!empty($migrations)) {
    $migrationFile = file_get_contents($migrations[0]);
    echo "Migration file: " . basename($migrations[0]) . "\n\n";

    // Extract date/time related lines
    $lines = explode("\n", $migrationFile);
    foreach ($lines as $line) {
        if (stripos($line, 'date') !== false || stripos($line, 'time') !== false || stripos($line, 'timestamp') !== false) {
            echo trim($line) . "\n";
        }
    }
} else {
    echo "❌ Migration file not found!\n";
}

echo "\n========================================\n";
