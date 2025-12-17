<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== All Database Tables ===\n\n";

$tables = DB::select('SHOW TABLES');
$databaseName = config('database.connections.mysql.database');

echo "Database: $databaseName\n";
echo str_repeat("=", 60) . "\n\n";

foreach ($tables as $table) {
    $tableArray = (array) $table;
    $tableName = reset($tableArray);

    // Get row count
    $count = DB::table($tableName)->count();

    // Get columns
    $columns = DB::select("SHOW COLUMNS FROM `$tableName`");
    $columnNames = array_map(fn($col) => $col->Field, $columns);

    echo "📊 Table: $tableName\n";
    echo "   Rows: $count\n";
    echo "   Columns: " . implode(', ', array_slice($columnNames, 0, 5));
    if (count($columnNames) > 5) {
        echo " ... (+" . (count($columnNames) - 5) . " more)";
    }
    echo "\n\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "Main Tables for Reports:\n";
echo str_repeat("=", 60) . "\n";

$mainTables = [
    'projects' => 'Projects (Main)',
    'vendors' => 'Vendors',
    'custs' => 'Customers',
    'ppms' => 'Project Managers',
    'aams' => 'Account Managers',
    'ds' => 'Delivery Specialists',
    'project_customers' => 'Project-Customer Relations',
    'project_vendors' => 'Project-Vendor Relations',
    'project_delivery_specialists' => 'Project-DS Relations',
];

foreach ($mainTables as $table => $description) {
    $count = DB::table($table)->count();
    echo "✓ $description: $count rows\n";
}
