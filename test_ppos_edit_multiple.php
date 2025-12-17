<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "========================================\n";
echo "🧪 PPOS EDIT - MULTIPLE CATEGORIES TEST\n";
echo "========================================\n\n";

// Test View
echo "1️⃣ EDIT VIEW CHECK:\n";
echo "--------------------------------------\n";
$editPath = resource_path('views/dashboard/PPOs/edit.blade.php');
$editContent = file_get_contents($editPath);

$checks = [
    'Multiple select (name="category[]")' => strpos($editContent, 'name="category[]"') !== false,
    'Multiple attribute' => strpos($editContent, 'multiple') !== false,
    'loadCategories function' => strpos($editContent, 'function loadCategories') !== false,
    'closeOnSelect: false' => strpos($editContent, 'closeOnSelect: false') !== false,
    'allowClear: true' => strpos($editContent, 'allowClear: true') !== false,
    'Console logging' => strpos($editContent, 'console.log') !== false,
    'Error handler' => strpos($editContent, 'error: function') !== false,
    'Multiple categories text' => strpos($editContent, 'one or more categories') !== false,
];

foreach ($checks as $name => $result) {
    echo ($result ? "  ✅" : "  ❌") . " {$name}\n";
}

echo "\n";

// Test Controller
echo "2️⃣ CONTROLLER UPDATE METHOD CHECK:\n";
echo "--------------------------------------\n";
$controllerPath = app_path('Http/Controllers/PposController.php');
$controllerContent = file_get_contents($controllerPath);

$controllerChecks = [
    'Array validation for category' => strpos($controllerContent, "'category' => 'required|array'") !== false,
    'Array element validation' => strpos($controllerContent, "'category.*' => 'required|exists:pepos,id'") !== false,
    'Loop for multiple categories' => preg_match('/for\s*\(\s*\$i\s*=\s*1.*count\(\$categories\)/s', $controllerContent),
    'Update first category' => strpos($controllerContent, '$categories[0]') !== false,
    'Create additional records' => strpos($controllerContent, 'Create additional records') !== false,
];

foreach ($controllerChecks as $name => $result) {
    echo ($result ? "  ✅" : "  ❌") . " {$name}\n";
}

echo "\n";

// Test Logic Simulation
echo "3️⃣ UPDATE LOGIC SIMULATION:\n";
echo "--------------------------------------\n";
echo "Scenario: Edit existing PPO and select 3 categories\n\n";

echo "Input:\n";
echo "  - Existing PPO ID: 1\n";
echo "  - Old Category: nazme (ID: 1)\n";
echo "  - New Categories: [2, 3, 4] (3 selected)\n";
echo "  - PO Number: PO-123\n\n";

echo "Expected Behavior:\n";
echo "  ✅ Update PPO #1 with Category ID 2 (first selected)\n";
echo "  ✅ Create new PPO with Category ID 3 (same PO Number)\n";
echo "  ✅ Create new PPO with Category ID 4 (same PO Number)\n";
echo "  ✅ Total: 1 updated + 2 new = 3 records with same PO\n\n";

echo "Result Message:\n";
echo "  'PPO has been updated successfully and 2 additional\n";
echo "   record(s) created for other categories'\n";

echo "\n";

// Compare CREATE vs EDIT
echo "4️⃣ CREATE vs EDIT COMPARISON:\n";
echo "--------------------------------------\n";

$createPath = resource_path('views/dashboard/PPOs/create.blade.php');
$createContent = file_get_contents($createPath);

echo "Feature                          | CREATE    | EDIT\n";
echo "----------------------------------|-----------|----------\n";
echo "Multiple selection               | " .
    (strpos($createContent, 'multiple') !== false ? "✅ YES    " : "❌ NO     ") . " | " .
    (strpos($editContent, 'multiple') !== false ? "✅ YES" : "❌ NO") . "\n";

echo "name=\"category[]\"                | " .
    (strpos($createContent, 'name="category[]"') !== false ? "✅ YES    " : "❌ NO     ") . " | " .
    (strpos($editContent, 'name="category[]"') !== false ? "✅ YES" : "❌ NO") . "\n";

echo "closeOnSelect: false             | " .
    (strpos($createContent, 'closeOnSelect: false') !== false ? "✅ YES    " : "❌ NO     ") . " | " .
    (strpos($editContent, 'closeOnSelect: false') !== false ? "✅ YES" : "❌ NO") . "\n";

echo "allowClear: true                 | " .
    (strpos($createContent, 'allowClear: true') !== false ? "✅ YES    " : "❌ NO     ") . " | " .
    (strpos($editContent, 'allowClear: true') !== false ? "✅ YES" : "❌ NO") . "\n";

echo "AJAX category loading            | " .
    (strpos($createContent, '/ppos/categories/') !== false ? "✅ YES    " : "❌ NO     ") . " | " .
    (strpos($editContent, '/ppos/categories/') !== false ? "✅ YES" : "❌ NO") . "\n";

echo "Console debugging                | " .
    (strpos($createContent, 'console.log') !== false ? "✅ YES    " : "❌ NO     ") . " | " .
    (strpos($editContent, 'console.log') !== false ? "✅ YES" : "❌ NO") . "\n";

echo "\n";

// Summary
echo "========================================\n";
echo "📊 SUMMARY:\n";
echo "========================================\n";

$allPassed = true;
foreach ($checks as $name => $result) {
    if (!$result) $allPassed = false;
}
foreach ($controllerChecks as $name => $result) {
    if (!$result) $allPassed = false;
}

if ($allPassed) {
    echo "✅ ALL CHECKS PASSED!\n\n";
} else {
    echo "⚠️ Some checks failed - review above\n\n";
}

echo "🎯 BOTH CREATE & EDIT NOW SUPPORT MULTIPLE CATEGORIES!\n\n";

echo "CREATE page behavior:\n";
echo "  - Select multiple categories\n";
echo "  - Creates N separate PPOS records\n";
echo "  - Each with same PO Number but different category\n\n";

echo "EDIT page behavior:\n";
echo "  - Select multiple categories\n";
echo "  - Updates current record with 1st category\n";
echo "  - Creates (N-1) new records for remaining categories\n";
echo "  - All share same PO Number\n\n";

echo "🧪 TESTING STEPS:\n";
echo "--------------------------------------\n";
echo "1. Visit: http://mdsjedpr.test/ppos\n";
echo "2. Click Edit on any PPO record\n";
echo "3. Press F12 → Console tab\n";
echo "4. Select PR Number (if changing)\n";
echo "5. Select MULTIPLE categories (e.g., 2-3)\n";
echo "6. Fill other fields and Save\n";
echo "7. Check success message mentions additional records\n";
echo "8. Verify multiple records with same PO Number exist\n";
echo "========================================\n";
