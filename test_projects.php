<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Testing Projects Model...\n";

    $projects = App\Models\projects::with([
        'vendor', 'cust', 'ds', 'aams', 'ppms',
        'customers', 'vendors', 'deliverySpecialists'
    ])->get();

    echo "Success! Found " . $projects->count() . " projects\n";

    foreach ($projects as $project) {
        echo "Project: " . $project->name . "\n";
        echo "- Customers: " . $project->customers->count() . "\n";
        echo "- Vendors: " . $project->vendors->count() . "\n";
        echo "- DS: " . $project->deliverySpecialists->count() . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
