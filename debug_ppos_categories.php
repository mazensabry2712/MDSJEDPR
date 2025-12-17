<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "🔍 PPOS CATEGORY LOADING DEBUG\n";
echo "========================================\n\n";

// Test 1: Check Projects
echo "1️⃣ PROJECTS CHECK:\n";
echo "--------------------------------------\n";
$projects = App\Models\Project::all();
echo "Total Projects: " . $projects->count() . "\n";
foreach ($projects as $p) {
    echo "  - ID: {$p->id}, PR Number: {$p->pr_number}, Name: {$p->name}\n";
}
echo "\n";

// Test 2: Check EPO Categories
echo "2️⃣ EPO CATEGORIES:\n";
echo "--------------------------------------\n";
$epos = App\Models\Pepo::all();
echo "Total EPO Records: " . $epos->count() . "\n";
foreach ($epos as $epo) {
    echo "  - ID: {$epo->id}, PR: {$epo->pr_number}, Category: {$epo->category}\n";
}
echo "\n";

// Test 3: Test API Endpoint for each project
echo "3️⃣ API ENDPOINT TEST:\n";
echo "--------------------------------------\n";
foreach ($projects as $project) {
    echo "Testing PR Number: {$project->pr_number} (ID: {$project->id})\n";

    // This is what the API does
    $categories = App\Models\Pepo::where('pr_number', $project->id)
        ->select('id', 'category')->get();

    echo "  Categories found: " . $categories->count() . "\n";
    if ($categories->isNotEmpty()) {
        foreach ($categories as $cat) {
            echo "    - ID: {$cat->id}, Category: {$cat->category}\n";
        }
    } else {
        echo "    ❌ NO CATEGORIES FOUND!\n";
    }
    echo "\n";
}

// Test 4: Check Controller Method
echo "4️⃣ CONTROLLER METHOD TEST:\n";
echo "--------------------------------------\n";
try {
    $controller = new App\Http\Controllers\PposController();
    echo "✅ PposController exists\n";

    // Check if method exists
    if (method_exists($controller, 'getCategoriesByProject')) {
        echo "✅ getCategoriesByProject() method exists\n";

        // Simulate API call for first project
        $project = $projects->first();
        if ($project) {
            echo "\nSimulating API call for PR ID: {$project->id}\n";
            $response = $controller->getCategoriesByProject($project->id);
            $data = $response->getData(true);

            echo "API Response:\n";
            echo json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
        }
    } else {
        echo "❌ getCategoriesByProject() method NOT FOUND!\n\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// Test 5: Check Route
echo "5️⃣ ROUTE CHECK:\n";
echo "--------------------------------------\n";
$routes = Illuminate\Support\Facades\Route::getRoutes();
$found = false;
foreach ($routes as $route) {
    if (strpos($route->uri(), 'ppos/categories') !== false) {
        echo "✅ Route found: " . $route->uri() . "\n";
        echo "   Methods: " . implode(', ', $route->methods()) . "\n";
        echo "   Action: " . $route->getActionName() . "\n";
        $found = true;
    }
}
if (!$found) {
    echo "❌ Route 'ppos/categories/{pr_number}' NOT FOUND!\n";
}
echo "\n";

// Test 6: Check View JavaScript
echo "6️⃣ VIEW JAVASCRIPT CHECK:\n";
echo "--------------------------------------\n";
$createView = file_get_contents(resource_path('views/dashboard/PPOs/create.blade.php'));

// Check for AJAX URL
if (preg_match('/url:\s*[\'"`](.+?ppos\/categories.+?)[\'"`]/', $createView, $matches)) {
    echo "✅ AJAX URL found: " . $matches[1] . "\n";
} else {
    echo "❌ AJAX URL not found in create.blade.php!\n";
}

// Check for loadCategories function
if (strpos($createView, 'function loadCategories') !== false) {
    echo "✅ loadCategories() function exists\n";
} else {
    echo "❌ loadCategories() function NOT FOUND!\n";
}

// Check for PR Number change event
if (strpos($createView, "change', function()") !== false || strpos($createView, "change\", function()") !== false) {
    echo "✅ PR Number change event listener exists\n";
} else {
    echo "❌ PR Number change event listener NOT FOUND!\n";
}

echo "\n";

// Test 7: Manual SQL Query
echo "7️⃣ MANUAL SQL QUERY:\n";
echo "--------------------------------------\n";
$project = $projects->first();
if ($project) {
    echo "Query: SELECT id, category FROM pepos WHERE pr_number = {$project->id}\n";
    $result = DB::select("SELECT id, category FROM pepos WHERE pr_number = ?", [$project->id]);
    echo "Results: " . count($result) . " records\n";
    foreach ($result as $r) {
        echo "  - ID: {$r->id}, Category: {$r->category}\n";
    }
}

echo "\n========================================\n";
echo "📊 DIAGNOSIS:\n";
echo "========================================\n";

$project = $projects->first();
$categoriesCount = App\Models\Pepo::where('pr_number', $project->id)->count();

if ($categoriesCount === 0) {
    echo "❌ PROBLEM: No categories exist for PR Number {$project->pr_number}\n";
    echo "   Solution: Create EPO categories first\n";
} elseif (!$found) {
    echo "❌ PROBLEM: Route not registered\n";
    echo "   Solution: Check routes/web.php\n";
} else {
    echo "✅ Everything looks correct!\n";
    echo "   If dropdown still empty:\n";
    echo "   1. Check browser console (F12) for JavaScript errors\n";
    echo "   2. Check Network tab for AJAX request\n";
    echo "   3. Verify Select2 is loaded\n";
}

echo "========================================\n";
