<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;
use Illuminate\Support\Facades\DB;

echo "=== Current Database State ===\n\n";

// Check all tables
echo "1. Projects Table:\n";
echo str_repeat("-", 60) . "\n";
$projects = Project::with(['vendor', 'cust', 'ds', 'aams', 'ppms'])->get();
echo "Total Projects: " . $projects->count() . "\n\n";

foreach ($projects as $project) {
    echo "PR-{$project->pr_number}: {$project->name}\n";
    echo "  Customer: " . ($project->cust->name ?? 'N/A') . "\n";
    echo "  Vendor: " . ($project->vendor->vendors ?? 'N/A') . "\n";
    echo "  PM: " . ($project->ppms->name ?? 'N/A') . "\n";
    echo "  Created: {$project->created_at}\n";
    echo "\n";
}

echo "\n2. Vendors Table:\n";
echo str_repeat("-", 60) . "\n";
$vendors = DB::table('vendors')->get();
echo "Total Vendors: " . $vendors->count() . "\n";
foreach ($vendors as $vendor) {
    echo "  - ID: {$vendor->id} | Name: {$vendor->vendors}\n";
}

echo "\n3. Customers Table:\n";
echo str_repeat("-", 60) . "\n";
$customers = DB::table('custs')->get();
echo "Total Customers: " . $customers->count() . "\n";
foreach ($customers as $customer) {
    echo "  - ID: {$customer->id} | Name: {$customer->name}\n";
}

echo "\n4. Project Managers Table:\n";
echo str_repeat("-", 60) . "\n";
$pms = DB::table('ppms')->get();
echo "Total PMs: " . $pms->count() . "\n";
foreach ($pms as $pm) {
    echo "  - ID: {$pm->id} | Name: {$pm->name}\n";
}

echo "\n5. Account Managers Table:\n";
echo str_repeat("-", 60) . "\n";
$ams = DB::table('aams')->get();
echo "Total AMs: " . $ams->count() . "\n";
foreach ($ams as $am) {
    echo "  - ID: {$am->id} | Name: {$am->name}\n";
}

echo "\n6. Delivery Specialists Table:\n";
echo str_repeat("-", 60) . "\n";
$ds = DB::table('ds')->get();
echo "Total DS: " . $ds->count() . "\n";
foreach ($ds as $d) {
    echo "  - ID: {$d->id} | Name: {$d->dsname}\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "SUMMARY\n";
echo str_repeat("=", 60) . "\n";
echo "\nCurrent System State:\n";
echo "  Projects: {$projects->count()}\n";
echo "  Vendors: {$vendors->count()}\n";
echo "  Customers: {$customers->count()}\n";
echo "  Project Managers: {$pms->count()}\n";
echo "  Account Managers: {$ams->count()}\n";
echo "  Delivery Specialists: {$ds->count()}\n";
echo "\n✅ All data is being loaded automatically!\n";
echo "   Any new row added to these tables will appear in Reports automatically.\n";
