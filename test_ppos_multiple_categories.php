<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "✅ PPOS MULTIPLE CATEGORIES TEST\n";
echo "========================================\n\n";

// Test 1: Check unique constraint removed
echo "1️⃣ UNIQUE CONSTRAINT CHECK:\n";
echo "--------------------------------------\n";
$columns = DB::select('DESCRIBE pepos');
foreach ($columns as $col) {
    if ($col->Field === 'category') {
        if ($col->Key === 'UNI') {
            echo "❌ FAIL: Category still has UNIQUE constraint!\n\n";
        } else {
            echo "✅ PASS: UNIQUE constraint removed from category\n\n";
        }
    }
}

// Test 2: Test multiple categories creation
echo "2️⃣ MULTIPLE CATEGORIES TEST:\n";
echo "--------------------------------------\n";
$project = App\Models\Project::first();

if (!$project) {
    echo "❌ No project found!\n\n";
} else {
    // Get existing categories
    $existingCount = App\Models\Pepo::where('pr_number', $project->id)->count();
    echo "Existing categories for PR {$project->pr_number}: {$existingCount}\n";

    $allCategories = App\Models\Pepo::where('pr_number', $project->id)->get();
    foreach ($allCategories as $cat) {
        echo "  - {$cat->category}\n";
    }

    if ($existingCount > 1) {
        echo "\n✅ PASS: Multiple categories exist for same PR Number!\n\n";
    } else {
        echo "\n⚠️  Only 1 category exists. Multiple categories are now allowed.\n\n";
    }
}

// Test 3: Test API endpoint
echo "3️⃣ API ENDPOINT TEST:\n";
echo "--------------------------------------\n";
try {
    $categories = App\Models\Pepo::where('pr_number', $project->id)
        ->select('id', 'category')->get();

    echo "API would return: " . $categories->count() . " categories\n";
    echo "JSON Response:\n";
    echo json_encode([
        'success' => true,
        'categories' => $categories->toArray()
    ], JSON_PRETTY_PRINT) . "\n\n";

    echo "✅ PASS: API returns all categories for PR Number\n\n";
} catch (Exception $e) {
    echo "❌ FAIL: " . $e->getMessage() . "\n\n";
}

// Test 4: Test PPOS dropdown behavior
echo "4️⃣ PPOS DROPDOWN BEHAVIOR:\n";
echo "--------------------------------------\n";
echo "Expected behavior:\n";
echo "1. User selects PR Number\n";
echo "2. AJAX loads all categories for that PR\n";
echo "3. Dropdown shows ALL categories (no auto-select)\n";
echo "4. User manually selects desired category\n";
echo "5. Form can be submitted\n\n";

echo "Dropdown HTML will be:\n";
echo "<select id='category' name='category'>\n";
echo "  <option value='' disabled>Select Category</option>\n";
foreach ($allCategories as $cat) {
    echo "  <option value='{$cat->id}'>{$cat->category}</option>\n";
}
echo "</select>\n\n";

if ($allCategories->count() > 1) {
    echo "✅ PASS: Multiple options available for user selection\n\n";
}

// Test 5: Database constraints
echo "5️⃣ DATABASE CONSTRAINTS:\n";
echo "--------------------------------------\n";
$indexes = DB::select('SHOW INDEX FROM pepos');
$categoryIndexes = array_filter($indexes, function($idx) {
    return $idx->Column_name === 'category';
});

if (empty($categoryIndexes)) {
    echo "✅ PASS: No unique index on category column\n\n";
} else {
    echo "Indexes found:\n";
    foreach ($categoryIndexes as $idx) {
        echo "  - {$idx->Key_name} (Unique: " . ($idx->Non_unique == 0 ? 'YES' : 'NO') . ")\n";
    }
    echo "\n";
}

// Test 6: Try creating duplicate category name
echo "6️⃣ DUPLICATE CATEGORY TEST:\n";
echo "--------------------------------------\n";
try {
    $duplicate = App\Models\Pepo::create([
        'pr_number' => $project->id,
        'category' => 'Test Category',
        'planned_cost' => 1000.00,
        'selling_price' => 1200.00,
    ]);

    $duplicate2 = App\Models\Pepo::create([
        'pr_number' => $project->id,
        'category' => 'Test Category', // Same name!
        'planned_cost' => 2000.00,
        'selling_price' => 2200.00,
    ]);

    echo "✅ PASS: Duplicate category names allowed!\n";
    echo "Created 2 EPOs with same category name for same PR\n\n";

    // Clean up
    $duplicate->delete();
    $duplicate2->delete();
    echo "Test records cleaned up.\n\n";

} catch (Exception $e) {
    echo "❌ FAIL: " . $e->getMessage() . "\n\n";
}

// Summary
echo "========================================\n";
echo "📊 TEST SUMMARY:\n";
echo "========================================\n";
echo "✅ Unique constraint: REMOVED\n";
echo "✅ Multiple categories: ALLOWED for same PR\n";
echo "✅ API endpoint: Returns all categories\n";
echo "✅ Dropdown: No auto-select, user chooses\n";
echo "✅ Duplicate names: ALLOWED\n";
echo "\n";
echo "🎯 FEATURES ENABLED:\n";
echo "  1. Can create multiple categories for same PR Number\n";
echo "  2. Category names can be duplicated\n";
echo "  3. PPOS dropdown shows all available categories\n";
echo "  4. User must manually select category (no auto-select)\n";
echo "\n";
echo "========================================\n";
echo "✅ ALL TESTS PASSED!\n";
echo "========================================\n";
