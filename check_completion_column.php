<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Projects Table Columns with 'completion' ===\n\n";

$columns = DB::select("SHOW COLUMNS FROM projects");

$found = false;
foreach ($columns as $column) {
    if (stripos($column->Field, 'complet') !== false) {
        echo "Column: {$column->Field}\n";
        echo "Type: {$column->Type}\n";
        echo "Null: {$column->Null}\n";
        echo "Default: {$column->Default}\n\n";
        $found = true;
    }
}

if (!$found) {
    echo "No columns with 'completion' found.\n";
    echo "\nAll columns in projects table:\n";
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type})\n";
    }
}
