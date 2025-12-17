<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;

echo "=== Testing All Tables in Reports ===\n\n";

$controller = new ReportController();
$request = new Request([]);
$response = $controller->index($request);
$data = $response->getData();

echo "📊 Tables Data Summary:\n";
echo str_repeat("=", 70) . "\n\n";

echo "1. Projects (Main Table):\n";
echo "   Total: " . $data['reports']->count() . " projects\n";
foreach ($data['reports'] as $i => $project) {
    echo "   " . ($i + 1) . ". PR-{$project->pr_number}: {$project->name}\n";
}
echo "\n";

echo "2. Vendors Table:\n";
echo "   Total: " . $data['allVendors']->count() . " vendors\n";
foreach ($data['allVendors'] as $i => $vendor) {
    echo "   " . ($i + 1) . ". {$vendor->vendors}\n";
}
echo "\n";

echo "3. Customers Table:\n";
echo "   Total: " . $data['allCustomers']->count() . " customers\n";
foreach ($data['allCustomers'] as $i => $customer) {
    echo "   " . ($i + 1) . ". {$customer->name}\n";
}
echo "\n";

echo "4. Project Managers Table:\n";
echo "   Total: " . $data['allProjectManagers']->count() . " PMs\n";
foreach ($data['allProjectManagers'] as $i => $pm) {
    echo "   " . ($i + 1) . ". {$pm->name}\n";
}
echo "\n";

echo "5. Account Managers Table:\n";
echo "   Total: " . $data['allAccountManagers']->count() . " AMs\n";
foreach ($data['allAccountManagers'] as $i => $am) {
    echo "   " . ($i + 1) . ". {$am->name}\n";
}
echo "\n";

echo "6. Delivery Specialists Table:\n";
echo "   Total: " . $data['allDeliverySpecialists']->count() . " DS\n";
foreach ($data['allDeliverySpecialists'] as $i => $ds) {
    echo "   " . ($i + 1) . ". {$ds->dsname}\n";
}
echo "\n";

echo "7. Project-Customer Relations:\n";
echo "   Total: " . $data['projectCustomers']->count() . " relations\n";
foreach ($data['projectCustomers'] as $i => $pc) {
    echo "   " . ($i + 1) . ". PR-{$pc->pr_number} ↔ {$pc->customer_name}\n";
}
echo "\n";

echo "8. Project-Vendor Relations:\n";
echo "   Total: " . $data['projectVendors']->count() . " relations\n";
foreach ($data['projectVendors'] as $i => $pv) {
    echo "   " . ($i + 1) . ". PR-{$pv->pr_number} ↔ {$pv->vendor_name}\n";
}
echo "\n";

echo "9. Project-DS Relations:\n";
echo "   Total: " . $data['projectDS']->count() . " relations\n";
foreach ($data['projectDS'] as $i => $pds) {
    echo "   " . ($i + 1) . ". PR-{$pds->pr_number} ↔ {$pds->dsname}\n";
}
echo "\n";

echo str_repeat("=", 70) . "\n";
echo "SUMMARY\n";
echo str_repeat("=", 70) . "\n\n";

echo "✅ Total Tables Displayed: 9 tables\n\n";

echo "Main Tables:\n";
echo "  ✓ Projects: {$data['reports']->count()} rows\n";
echo "  ✓ Vendors: {$data['allVendors']->count()} rows\n";
echo "  ✓ Customers: {$data['allCustomers']->count()} rows\n";
echo "  ✓ Project Managers: {$data['allProjectManagers']->count()} rows\n";
echo "  ✓ Account Managers: {$data['allAccountManagers']->count()} rows\n";
echo "  ✓ Delivery Specialists: {$data['allDeliverySpecialists']->count()} rows\n\n";

echo "Relationship Tables:\n";
echo "  ✓ Project-Customer Relations: {$data['projectCustomers']->count()} rows\n";
echo "  ✓ Project-Vendor Relations: {$data['projectVendors']->count()} rows\n";
echo "  ✓ Project-DS Relations: {$data['projectDS']->count()} rows\n\n";

$totalRows = $data['reports']->count() +
             $data['allVendors']->count() +
             $data['allCustomers']->count() +
             $data['allProjectManagers']->count() +
             $data['allAccountManagers']->count() +
             $data['allDeliverySpecialists']->count() +
             $data['projectCustomers']->count() +
             $data['projectVendors']->count() +
             $data['projectDS']->count();

echo "📈 Grand Total: $totalRows rows across all tables\n\n";

echo "🎉 All tables loaded successfully!\n";
echo "💡 View them at: http://mdsjedpr.test/reports\n";
