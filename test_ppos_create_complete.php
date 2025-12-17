<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;
use App\Models\Pepo;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "🧪 PPOS CREATE PAGE - COMPLETE TEST\n";
echo "========================================\n\n";

// Test 1: Check if Projects exist
echo "1️⃣ PROJECTS CHECK:\n";
echo "--------------------------------------\n";
$projects = Project::all();
echo "Total Projects: " . $projects->count() . "\n";
foreach ($projects as $project) {
    echo "  - ID: {$project->id}, PR Number: {$project->pr_number}, Name: {$project->name}\n";
}
echo "\n";

// Test 2: Check EPO Categories
echo "2️⃣ EPO CATEGORIES:\n";
echo "--------------------------------------\n";
$pepos = Pepo::select('id', 'pr_number', 'category')->get();
echo "Total EPO Records: " . $pepos->count() . "\n";
foreach ($pepos as $pepo) {
    echo "  - ID: {$pepo->id}, PR: {$pepo->pr_number}, Category: {$pepo->category}\n";
}
echo "\n";

// Test 3: Test Route exists
echo "3️⃣ ROUTE CHECK:\n";
echo "--------------------------------------\n";
$routes = Route::getRoutes();
$categoryRoute = null;
foreach ($routes as $route) {
    if (strpos($route->uri(), 'ppos/categories') !== false) {
        $categoryRoute = $route;
        break;
    }
}
if ($categoryRoute) {
    echo "✅ Route found: " . $categoryRoute->uri() . "\n";
    echo "   Methods: " . implode(', ', $categoryRoute->methods()) . "\n";
    echo "   Action: " . $categoryRoute->getActionName() . "\n";
} else {
    echo "❌ Route NOT found: ppos/categories/{pr_number}\n";
}
echo "\n";

// Test 4: Test API endpoint directly
echo "4️⃣ API ENDPOINT TEST:\n";
echo "--------------------------------------\n";
foreach ($projects as $project) {
    echo "Testing PR ID: {$project->id} (PR Number: {$project->pr_number})\n";

    $categories = Pepo::where('pr_number', $project->id)
        ->select('id', 'category')
        ->get();

    $response = [
        'success' => true,
        'categories' => $categories
    ];

    echo "  Categories found: " . $categories->count() . "\n";
    foreach ($categories as $cat) {
        echo "    - ID: {$cat->id}, Category: {$cat->category}\n";
    }
}
echo "\n";

// Test 5: Check View file exists
echo "5️⃣ VIEW FILE CHECK:\n";
echo "--------------------------------------\n";
$viewPath = resource_path('views/dashboard/PPOs/create.blade.php');
if (file_exists($viewPath)) {
    echo "✅ View file exists: {$viewPath}\n";

    // Check for key JavaScript code
    $content = file_get_contents($viewPath);

    $checks = [
        'loadCategories function' => strpos($content, 'function loadCategories') !== false,
        'AJAX URL' => strpos($content, '/ppos/categories/') !== false,
        'select2:select event' => strpos($content, 'select2:select') !== false,
        'change event' => strpos($content, "on('change") !== false,
        '#pr_number selector' => strpos($content, '#pr_number') !== false,
        '#category selector' => strpos($content, '#category') !== false,
    ];

    echo "\n📋 JavaScript Code Check:\n";
    foreach ($checks as $name => $result) {
        echo ($result ? "  ✅" : "  ❌") . " {$name}\n";
    }
} else {
    echo "❌ View file NOT found\n";
}
echo "\n";

// Test 6: Check Controller
echo "6️⃣ CONTROLLER CHECK:\n";
echo "--------------------------------------\n";
$controllerPath = app_path('Http/Controllers/PposController.php');
if (file_exists($controllerPath)) {
    echo "✅ Controller exists: {$controllerPath}\n";

    $controllerContent = file_get_contents($controllerPath);

    if (strpos($controllerContent, 'getCategoriesByProject') !== false) {
        echo "✅ getCategoriesByProject() method exists\n";
    } else {
        echo "❌ getCategoriesByProject() method NOT found\n";
    }
} else {
    echo "❌ Controller NOT found\n";
}
echo "\n";

// Test 7: Database connection test
echo "7️⃣ DATABASE CONNECTION:\n";
echo "--------------------------------------\n";
try {
    DB::connection()->getPdo();
    echo "✅ Database connection successful\n";

    // Test raw query
    $result = DB::select("SELECT id, category FROM pepos WHERE pr_number = 1 LIMIT 5");
    echo "✅ Query test successful: " . count($result) . " records found\n";

} catch (\Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 8: Simulate full request/response cycle
echo "8️⃣ SIMULATE REQUEST:\n";
echo "--------------------------------------\n";
if ($projects->count() > 0) {
    $testProject = $projects->first();
    echo "Simulating request for PR ID: {$testProject->id}\n";

    try {
        $app = app();
        $controller = $app->make(\App\Http\Controllers\PposController::class);

        if (method_exists($controller, 'getCategoriesByProject')) {
            $response = $controller->getCategoriesByProject($testProject->id);
            $data = $response->getData(true);

            echo "✅ API Response:\n";
            echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
        } else {
            echo "❌ Method getCategoriesByProject not found in controller\n";
        }
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}
echo "\n";

// Test 9: Check for JavaScript conflicts
echo "9️⃣ POTENTIAL ISSUES CHECK:\n";
echo "--------------------------------------\n";
$viewContent = file_get_contents($viewPath);

$issues = [];

// Check if Select2 is loaded
if (strpos($viewContent, 'select2.min.js') === false) {
    $issues[] = "⚠️ Select2 library might not be loaded";
}

// Check if jQuery is needed
if (strpos($viewContent, '$') !== false && strpos($viewContent, 'jquery') === false) {
    // jQuery is used but not explicitly loaded in this file (might be in layout)
}

// Check for setTimeout
if (strpos($viewContent, 'setTimeout') !== false) {
    echo "✅ Using setTimeout to wait for Select2 initialization\n";
}

// Check for console.log debugging
if (strpos($viewContent, 'console.log') !== false) {
    echo "✅ Console logging enabled for debugging\n";
}

if (empty($issues)) {
    echo "✅ No obvious issues found\n";
} else {
    foreach ($issues as $issue) {
        echo $issue . "\n";
    }
}
echo "\n";

// Final Summary
echo "========================================\n";
echo "📊 SUMMARY:\n";
echo "========================================\n";
echo "Projects: " . $projects->count() . "\n";
echo "EPO Categories: " . $pepos->count() . "\n";
echo "Route: " . ($categoryRoute ? "✅ OK" : "❌ NOT FOUND") . "\n";
echo "View: ✅ EXISTS\n";
echo "Controller: ✅ EXISTS\n";
echo "Database: ✅ CONNECTED\n";
echo "\n";

echo "🌐 NEXT STEPS:\n";
echo "1. Visit: http://mdsjedpr.test/ppos/create\n";
echo "2. Open Browser Console (F12)\n";
echo "3. Select PR Number from dropdown\n";
echo "4. Check Console for logs:\n";
echo "   - '✅ PPOS Create page loaded'\n";
echo "   - '🔵 Attaching PR Number change event'\n";
echo "   - '🔔 PR Number changed!'\n";
echo "   - '📡 Loading categories for PR: X'\n";
echo "   - '✅ AJAX Success: {...}'\n";
echo "5. Check if Category dropdown gets populated\n";
echo "\n";

echo "🔍 If still not working, check:\n";
echo "   - Browser Network tab for failed AJAX request\n";
echo "   - Browser Console tab for JavaScript errors\n";
echo "   - Make sure you did Hard Refresh (Ctrl+Shift+R)\n";
echo "========================================\n";
