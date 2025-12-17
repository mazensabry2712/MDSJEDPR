<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Customer PDF Setup ===\n\n";

// Test 1: Check if Cust model exists and has data
echo "1. Checking Customers in database:\n";
$customers = App\Models\Cust::all();
echo "   Total customers: " . $customers->count() . "\n";

if ($customers->count() > 0) {
    echo "\n2. Customer details:\n";
    foreach ($customers as $index => $customer) {
        echo "   Customer #" . ($index + 1) . ":\n";
        echo "      Name: " . ($customer->name ?? 'N/A') . "\n";
        echo "      Abb: " . ($customer->abb ?? 'N/A') . "\n";
        echo "      Type: " . ($customer->tybe ?? 'N/A') . "\n";
        echo "      Contact: " . ($customer->customercontactname ?? 'N/A') . "\n";
        echo "      Position: " . ($customer->customercontactposition ?? 'N/A') . "\n";
        echo "      Email: " . ($customer->email ?? 'N/A') . "\n";
        echo "      Phone: " . ($customer->phone ?? 'N/A') . "\n";
        echo "\n";
    }
}

// Test 2: Check if route exists
echo "3. Checking routes:\n";
$routes = collect(app('router')->getRoutes())->filter(function($route) {
    return str_contains($route->uri(), 'customer');
});

foreach ($routes as $route) {
    if (in_array($route->uri(), ['customer/export/pdf', 'customer/print'])) {
        echo "   ✓ " . $route->uri() . " -> " . $route->getActionName() . "\n";
    }
}

// Test 3: Check if controller methods exist
echo "\n4. Checking CustController methods:\n";
$controller = new App\Http\Controllers\CustController();
if (method_exists($controller, 'exportPDF')) {
    echo "   ✓ exportPDF() method exists\n";
} else {
    echo "   ✗ exportPDF() method NOT found\n";
}

if (method_exists($controller, 'printView')) {
    echo "   ✓ printView() method exists\n";
} else {
    echo "   ✗ printView() method NOT found\n";
}

// Test 4: Check if print view exists
echo "\n5. Checking print view file:\n";
$viewPath = resource_path('views/dashboard/customer/print.blade.php');
if (file_exists($viewPath)) {
    echo "   ✓ print.blade.php exists\n";
    echo "   File size: " . filesize($viewPath) . " bytes\n";
} else {
    echo "   ✗ print.blade.php NOT found\n";
}

echo "\n=== Test Complete ===\n";
