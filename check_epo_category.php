<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "📊 EPO CATEGORY CHECK\n";
echo "========================================\n\n";

// Check current EPO records
$epos = App\Models\Pepo::all();
echo "Total EPO records: " . $epos->count() . "\n\n";

if ($epos->isNotEmpty()) {
    echo "Current EPO Records:\n";
    echo "--------------------------------------\n";
    foreach ($epos as $epo) {
        echo "ID: {$epo->id} | PR: {$epo->pr_number} | Category: {$epo->category}\n";
    }
    echo "\n";
}

// Check table structure
echo "Table Structure:\n";
echo "--------------------------------------\n";
$columns = DB::select('DESCRIBE pepos');
foreach ($columns as $col) {
    if ($col->Field === 'category') {
        echo "Field: {$col->Field}\n";
        echo "Type: {$col->Type}\n";
        echo "Null: {$col->Null}\n";
        echo "Key: {$col->Key}\n";
        echo "Default: {$col->Default}\n";
        echo "Extra: {$col->Extra}\n\n";
        
        if ($col->Key === 'UNI') {
            echo "⚠️  Category has UNIQUE constraint!\n";
            echo "Need to remove it to allow duplicate categories.\n\n";
        }
    }
}

// Check indexes
echo "Indexes on pepos table:\n";
echo "--------------------------------------\n";
$indexes = DB::select('SHOW INDEX FROM pepos');
foreach ($indexes as $index) {
    if ($index->Column_name === 'category') {
        echo "Index Name: {$index->Key_name}\n";
        echo "Column: {$index->Column_name}\n";
        echo "Unique: " . ($index->Non_unique == 0 ? 'YES' : 'NO') . "\n\n";
    }
}

echo "========================================\n";
