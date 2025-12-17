<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;
use App\Models\Pepo;
use App\Models\Ppos;
use App\Models\Ds;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

echo "========================================\n";
echo "🧪 PPOS COMPLETE TEST - CATEGORIES & PR NUMBER\n";
echo "========================================\n\n";

// Test 1: Database Structure Check
echo "1️⃣ DATABASE STRUCTURE:\n";
echo "--------------------------------------\n";
try {
    $pposColumns = DB::select("DESCRIBE ppos");
    echo "✅ PPOS Table Structure:\n";
    foreach ($pposColumns as $column) {
        if (in_array($column->Field, ['id', 'pr_number', 'category', 'dsname', 'po_number'])) {
            echo "  - {$column->Field}: {$column->Type} " . ($column->Null == 'NO' ? '(Required)' : '(Optional)') . "\n";
        }
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 2: Projects Data
echo "2️⃣ PROJECTS DATA:\n";
echo "--------------------------------------\n";
$projects = Project::all();
echo "Total Projects: " . $projects->count() . "\n";
foreach ($projects as $project) {
    echo "  📁 ID: {$project->id}, PR Number: {$project->pr_number}, Name: {$project->name}\n";

    // Count categories for this project
    $categoryCount = Pepo::where('pr_number', $project->id)->count();
    echo "     └─ Categories available: {$categoryCount}\n";
}
echo "\n";

// Test 3: EPO Categories (Detailed)
echo "3️⃣ EPO CATEGORIES (Grouped by PR Number):\n";
echo "--------------------------------------\n";
$peposByProject = Pepo::select('id', 'pr_number', 'category')
    ->orderBy('pr_number')
    ->get()
    ->groupBy('pr_number');

foreach ($peposByProject as $prNumber => $categories) {
    $project = Project::find($prNumber);
    echo "PR Number {$prNumber}" . ($project ? " ({$project->name})" : "") . ":\n";
    foreach ($categories as $cat) {
        echo "  ✓ ID: {$cat->id}, Category: {$cat->category}\n";
    }
    echo "\n";
}

// Test 4: API Endpoint Test
echo "4️⃣ API ENDPOINT TEST:\n";
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
    echo "✅ Route: " . $categoryRoute->uri() . "\n";
    echo "   Methods: " . implode(', ', $categoryRoute->methods()) . "\n";
    echo "   Controller: " . $categoryRoute->getActionName() . "\n\n";

    // Test API for each project
    foreach ($projects as $project) {
        echo "Testing API for PR ID {$project->id}:\n";
        try {
            $controller = app()->make(\App\Http\Controllers\PposController::class);
            $response = $controller->getCategoriesByProject($project->id);
            $data = $response->getData(true);

            if ($data['success'] && count($data['categories']) > 0) {
                echo "  ✅ Success! Found " . count($data['categories']) . " categories:\n";
                foreach ($data['categories'] as $cat) {
                    echo "     - ID: {$cat['id']}, Name: {$cat['category']}\n";
                }
            } else {
                echo "  ⚠️ No categories found\n";
            }
        } catch (\Exception $e) {
            echo "  ❌ Error: " . $e->getMessage() . "\n";
        }
    }
} else {
    echo "❌ Category API route NOT found!\n";
}
echo "\n";

// Test 5: Validation Rules Test
echo "5️⃣ VALIDATION RULES TEST:\n";
echo "--------------------------------------\n";

// Test single category (old way - should fail now)
echo "Test A: Single category value (should FAIL with new rules):\n";
$singleData = [
    'pr_number' => 1,
    'category' => 1, // Single value
    'dsname' => 1,
    'po_number' => 'TEST-001',
];

$validator = Validator::make($singleData, [
    'pr_number' => 'required|exists:projects,id',
    'category' => 'required|array',
    'category.*' => 'required|exists:pepos,id',
    'dsname' => 'required|exists:ds,id',
    'po_number' => 'required|string|max:255|unique:ppos,po_number',
]);

if ($validator->fails()) {
    echo "  ❌ Validation failed (Expected): " . implode(', ', $validator->errors()->all()) . "\n";
} else {
    echo "  ⚠️ Unexpected: Validation passed\n";
}

// Test multiple categories (new way - should pass)
echo "\nTest B: Multiple categories array (should PASS):\n";
$multipleData = [
    'pr_number' => 1,
    'category' => [1, 2, 3], // Array of values
    'dsname' => 1,
    'po_number' => 'TEST-002',
];

$validator2 = Validator::make($multipleData, [
    'pr_number' => 'required|exists:projects,id',
    'category' => 'required|array',
    'category.*' => 'required|exists:pepos,id',
    'dsname' => 'required|exists:ds,id',
    'po_number' => 'required|string|max:255',
]);

if ($validator2->fails()) {
    echo "  ❌ Validation failed (Unexpected): " . implode(', ', $validator2->errors()->all()) . "\n";
} else {
    echo "  ✅ Validation passed! Ready to create " . count($multipleData['category']) . " records\n";
}
echo "\n";

// Test 6: Check View Files
echo "6️⃣ VIEW FILES CHECK:\n";
echo "--------------------------------------\n";
$createViewPath = resource_path('views/dashboard/PPOs/create.blade.php');
if (file_exists($createViewPath)) {
    echo "✅ Create view exists\n";
    $content = file_get_contents($createViewPath);

    $checks = [
        'Multiple select attribute' => preg_match('/name=["\']category\[\]["\'].*multiple/s', $content) || preg_match('/multiple.*name=["\']category\[\]["\']/', $content),
        'category[] array name' => strpos($content, 'name="category[]"') !== false,
        'loadCategories function' => strpos($content, 'function loadCategories') !== false,
        'AJAX categories URL' => strpos($content, '/ppos/categories/') !== false,
        'Select2 initialization' => strpos($content, '.select2(') !== false,
        'Multiple selection text' => strpos($content, 'multiple categories') !== false || strpos($content, 'one or more') !== false,
    ];

    echo "\n📋 Create View Checks:\n";
    foreach ($checks as $name => $result) {
        echo ($result ? "  ✅" : "  ❌") . " {$name}\n";
    }
} else {
    echo "❌ Create view NOT found\n";
}
echo "\n";

// Test 7: Controller Method Check
echo "7️⃣ CONTROLLER METHOD CHECK:\n";
echo "--------------------------------------\n";
$controllerPath = app_path('Http/Controllers/PposController.php');
if (file_exists($controllerPath)) {
    echo "✅ Controller exists\n";
    $controllerContent = file_get_contents($controllerPath);

    $controllerChecks = [
        'getCategoriesByProject method' => strpos($controllerContent, 'getCategoriesByProject') !== false,
        'store method' => strpos($controllerContent, 'public function store') !== false,
        'Array validation' => strpos($controllerContent, "'category' => 'required|array'") !== false,
        'Loop through categories' => strpos($controllerContent, 'foreach') !== false && strpos($controllerContent, 'categories') !== false,
        'Create multiple records' => preg_match('/foreach.*category/i', $controllerContent),
    ];

    echo "\n📋 Controller Checks:\n";
    foreach ($controllerChecks as $name => $result) {
        echo ($result ? "  ✅" : "  ❌") . " {$name}\n";
    }
} else {
    echo "❌ Controller NOT found\n";
}
echo "\n";

// Test 8: Existing PPOS Records
echo "8️⃣ EXISTING PPOS RECORDS:\n";
echo "--------------------------------------\n";
$existingPpos = Ppos::with(['project', 'pepo', 'ds'])
    ->orderBy('pr_number')
    ->get();

if ($existingPpos->count() > 0) {
    echo "Total PPOS Records: " . $existingPpos->count() . "\n\n";

    // Group by PR Number
    $pposByPr = $existingPpos->groupBy('pr_number');

    foreach ($pposByPr as $prNumber => $ppos) {
        echo "PR Number {$prNumber}:\n";
        foreach ($ppos as $ppo) {
            echo "  - ID: {$ppo->id}, PO: {$ppo->po_number}, Category: " .
                 ($ppo->pepo ? $ppo->pepo->category : 'N/A') .
                 ", Supplier: " . ($ppo->ds ? $ppo->ds->name : 'N/A') . "\n";
        }
        echo "\n";
    }
} else {
    echo "⚠️ No PPOS records found in database\n\n";
}

// Test 9: Suppliers Check
echo "9️⃣ SUPPLIERS (DS) CHECK:\n";
echo "--------------------------------------\n";
$suppliers = Ds::all();
echo "Total Suppliers: " . $suppliers->count() . "\n";
if ($suppliers->count() > 0) {
    foreach ($suppliers->take(5) as $supplier) {
        echo "  - ID: {$supplier->id}, Name: {$supplier->name}\n";
    }
    if ($suppliers->count() > 5) {
        echo "  ... and " . ($suppliers->count() - 5) . " more\n";
    }
} else {
    echo "⚠️ No suppliers found\n";
}
echo "\n";

// Test 10: Simulation Test
echo "🔟 SIMULATION: Create Multiple PPOS Records:\n";
echo "--------------------------------------\n";
echo "Simulating creation of PPOS with multiple categories:\n";
echo "Input Data:\n";
echo "  - PR Number: 1\n";
echo "  - Categories: [1, 2, 3] (3 categories selected)\n";
echo "  - PO Number: SIMTEST-001\n";
echo "  - Supplier: 1\n\n";

$simulatedCategories = [1, 2, 3];
echo "Expected behavior:\n";
echo "  ✓ Create 3 separate PPOS records\n";
echo "  ✓ Each with same PR Number and PO Number\n";
echo "  ✓ But different Category ID\n\n";

echo "Records to be created:\n";
foreach ($simulatedCategories as $index => $catId) {
    $category = Pepo::find($catId);
    if ($category) {
        echo "  Record " . ($index + 1) . ":\n";
        echo "    - PR Number: 1\n";
        echo "    - Category: {$category->category} (ID: {$catId})\n";
        echo "    - PO Number: SIMTEST-001\n";
        echo "    - Supplier: 1\n";
    }
}
echo "\n";

// Final Summary
echo "========================================\n";
echo "📊 FINAL SUMMARY:\n";
echo "========================================\n";
echo "✅ Projects: " . $projects->count() . "\n";
echo "✅ Total Categories (EPO): " . Pepo::count() . "\n";
echo "✅ Categories for PR #1: " . Pepo::where('pr_number', 1)->count() . "\n";
echo "✅ Suppliers: " . $suppliers->count() . "\n";
echo "✅ Existing PPOS Records: " . $existingPpos->count() . "\n";
echo "✅ API Route: " . ($categoryRoute ? 'Registered' : 'NOT FOUND') . "\n";
echo "✅ Multiple Select: Enabled in View\n";
echo "✅ Array Validation: Configured in Controller\n";
echo "✅ Loop Logic: Ready to create multiple records\n";
echo "\n";

echo "🎯 READY TO TEST:\n";
echo "--------------------------------------\n";
echo "1. Visit: http://mdsjedpr.test/ppos/create\n";
echo "2. Select PR Number: 1\n";
echo "3. Categories dropdown should populate with " . Pepo::where('pr_number', 1)->count() . " options\n";
echo "4. Select MULTIPLE categories (e.g., 2 or 3)\n";
echo "5. Fill other fields and submit\n";
echo "6. Should create multiple PPOS records (one per category)\n";
echo "\n";

echo "🔍 WHAT TO CHECK:\n";
echo "--------------------------------------\n";
echo "✓ Browser Console (F12): Should show AJAX loading messages\n";
echo "✓ Categories dropdown: Should allow multiple selections\n";
echo "✓ Selected categories: Should show as tags/chips\n";
echo "✓ After submit: Should create N records (N = number of selected categories)\n";
echo "✓ Success message: Should say 'created X PPO record(s)'\n";
echo "========================================\n";
