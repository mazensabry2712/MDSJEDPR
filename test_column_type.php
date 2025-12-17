<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Projects Table Structure ===\n\n";

$columns = DB::select("DESCRIBE projects");

foreach ($columns as $column) {
    if (in_array($column->Field, ['pr_number', 'name', 'customer_po'])) {
        echo "Column: {$column->Field}\n";
        echo "  Type: {$column->Type}\n";
        echo "  Null: {$column->Null}\n";
        echo "  Key: {$column->Key}\n\n";
    }
}

echo "=== Actual Data ===\n\n";

$projects = DB::table('projects')->get(['id', 'pr_number', 'name']);

foreach ($projects as $project) {
    echo "ID: {$project->id} | PR: {$project->pr_number} (type: " . gettype($project->pr_number) . ") | Name: {$project->name}\n";
}
