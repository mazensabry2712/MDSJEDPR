<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "🧪 PPOS CATEGORY AUTO-LOAD TEST\n";
echo "========================================\n\n";

// Test 1: Check Database Connection
echo "📊 Test 1: Database Connection\n";
echo "--------------------------------------\n";
try {
    $projectCount = App\Models\Project::count();
    $epoCount = App\Models\Pepo::count();
    $pposCount = App\Models\Ppos::count();

    echo "✅ Projects: $projectCount\n";
    echo "✅ EPO Records: $epoCount\n";
    echo "✅ PPOS Records: $pposCount\n";
    echo "\n";
} catch (Exception $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 2: Check EPO Data
echo "📋 Test 2: EPO Data Structure\n";
echo "--------------------------------------\n";
try {
    $epos = App\Models\Pepo::with('project')->get();

    if ($epos->isEmpty()) {
        echo "⚠️  No EPO records found!\n";
        echo "💡 Solution: Add EPO records first\n\n";
    } else {
        foreach ($epos as $epo) {
            echo "EPO ID: {$epo->id}\n";
            echo "  - PR Number ID: {$epo->pr_number}\n";
            echo "  - Project: " . ($epo->project ? $epo->project->name : 'Not Found') . "\n";
            echo "  - Category: {$epo->category}\n";
            echo "\n";
        }
    }
} catch (Exception $e) {
    echo "❌ EPO Error: " . $e->getMessage() . "\n\n";
}

// Test 3: Test API Endpoint
echo "🔌 Test 3: API Endpoint Test\n";
echo "--------------------------------------\n";
try {
    $projects = App\Models\Project::all();

    if ($projects->isEmpty()) {
        echo "⚠️  No projects found!\n\n";
    } else {
        foreach ($projects as $project) {
            echo "Testing PR Number: {$project->pr_number} (ID: {$project->id})\n";

            // Simulate API call
            $categories = App\Models\Pepo::where('pr_number', $project->id)
                ->select('id', 'category')
                ->get();

            if ($categories->isEmpty()) {
                echo "  ⚠️  No categories found for this project\n";
                echo "  💡 Solution: Add EPO category for PR: {$project->pr_number}\n";
            } else {
                echo "  ✅ Found {$categories->count()} category/categories:\n";
                foreach ($categories as $cat) {
                    echo "     - ID: {$cat->id}, Category: {$cat->category}\n";
                }
            }
            echo "\n";
        }
    }
} catch (Exception $e) {
    echo "❌ API Test Error: " . $e->getMessage() . "\n\n";
}

// Test 4: Check Routes
echo "🛣️  Test 4: Route Check\n";
echo "--------------------------------------\n";
try {
    $routes = Route::getRoutes();
    $found = false;

    foreach ($routes as $route) {
        if (strpos($route->uri(), 'ppos/categories') !== false) {
            echo "✅ Route Found: " . $route->uri() . "\n";
            echo "   Method: " . implode('|', $route->methods()) . "\n";
            echo "   Name: " . $route->getName() . "\n";
            $found = true;
            break;
        }
    }

    if (!$found) {
        echo "❌ Route 'ppos/categories/{pr_number}' NOT FOUND!\n";
        echo "💡 Solution: Add route in web.php\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ Route Error: " . $e->getMessage() . "\n\n";
}

// Test 5: Controller Method Check
echo "🎛️  Test 5: Controller Method Check\n";
echo "--------------------------------------\n";
try {
    $controller = new App\Http\Controllers\PposController();

    if (method_exists($controller, 'getCategoriesByProject')) {
        echo "✅ Method 'getCategoriesByProject' exists in PposController\n";

        // Test with actual data
        if (!$projects->isEmpty()) {
            $firstProject = $projects->first();
            echo "   Testing with Project ID: {$firstProject->id}\n";

            $response = $controller->getCategoriesByProject($firstProject->id);
            $data = json_decode($response->getContent(), true);

            if ($data['success']) {
                echo "   ✅ API Response: Success\n";
                echo "   Categories Count: " . count($data['categories']) . "\n";
            } else {
                echo "   ⚠️  API Response: Failed\n";
            }
        }
    } else {
        echo "❌ Method 'getCategoriesByProject' NOT FOUND!\n";
        echo "💡 Solution: Add method to PposController\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ Controller Error: " . $e->getMessage() . "\n\n";
}

// Test 6: View Files Check
echo "👁️  Test 6: View Files Check\n";
echo "--------------------------------------\n";
$createView = resource_path('views/dashboard/PPOs/create.blade.php');
$editView = resource_path('views/dashboard/PPOs/edit.blade.php');

if (file_exists($createView)) {
    echo "✅ create.blade.php exists\n";
    $content = file_get_contents($createView);

    if (strpos($content, 'loadCategories') !== false) {
        echo "   ✅ Contains 'loadCategories' function\n";
    } else {
        echo "   ❌ Missing 'loadCategories' function\n";
    }

    if (strpos($content, '/ppos/categories/') !== false) {
        echo "   ✅ Contains AJAX URL '/ppos/categories/'\n";
    } else {
        echo "   ❌ Missing AJAX URL\n";
    }
} else {
    echo "❌ create.blade.php NOT FOUND!\n";
}

if (file_exists($editView)) {
    echo "✅ edit.blade.php exists\n";
    $content = file_get_contents($editView);

    if (strpos($content, 'loadCategories') !== false) {
        echo "   ✅ Contains 'loadCategories' function\n";
    } else {
        echo "   ❌ Missing 'loadCategories' function\n";
    }
} else {
    echo "❌ edit.blade.php NOT FOUND!\n";
}
echo "\n";

// Test 7: Check for common issues
echo "🔍 Test 7: Common Issues Check\n";
echo "--------------------------------------\n";

// Check if category field in pepos is nullable
try {
    $tableName = 'pepos';
    $columns = DB::select("SHOW COLUMNS FROM $tableName WHERE Field = 'category'");

    if (!empty($columns)) {
        $categoryColumn = $columns[0];
        echo "✅ Category column exists in pepos table\n";
        echo "   Type: {$categoryColumn->Type}\n";
        echo "   Null: {$categoryColumn->Null}\n";
    }
} catch (Exception $e) {
    echo "⚠️  Could not check table structure\n";
}
echo "\n";

// Summary
echo "========================================\n";
echo "📊 TEST SUMMARY\n";
echo "========================================\n";

$issues = [];

if ($epoCount == 0) {
    $issues[] = "No EPO records - Add EPO data first";
}

if ($projectCount == 0) {
    $issues[] = "No Projects - Add projects first";
}

$hasRoute = false;
foreach ($routes as $route) {
    if (strpos($route->uri(), 'ppos/categories') !== false) {
        $hasRoute = true;
        break;
    }
}
if (!$hasRoute) {
    $issues[] = "Missing route 'ppos/categories/{pr_number}'";
}

if (!method_exists(new App\Http\Controllers\PposController(), 'getCategoriesByProject')) {
    $issues[] = "Missing getCategoriesByProject method in controller";
}

if (empty($issues)) {
    echo "✅ All tests passed!\n";
    echo "🎉 System is ready to use!\n";
} else {
    echo "⚠️  Found " . count($issues) . " issue(s):\n\n";
    foreach ($issues as $i => $issue) {
        echo ($i + 1) . ". " . $issue . "\n";
    }
}

echo "\n========================================\n";
echo "Test completed at: " . date('Y-m-d H:i:s') . "\n";
echo "========================================\n";
