<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;
use App\Models\Pepo;
use App\Models\Ppos;

echo "========================================\n";
echo "🧪 PPOS CREATE vs EDIT - COMPARISON TEST\n";
echo "========================================\n\n";

// Test 1: View Files Comparison
echo "1️⃣ VIEW FILES COMPARISON:\n";
echo "--------------------------------------\n";

$createPath = resource_path('views/dashboard/PPOs/create.blade.php');
$editPath = resource_path('views/dashboard/PPOs/edit.blade.php');

$createContent = file_get_contents($createPath);
$editContent = file_get_contents($editPath);

echo "CREATE VIEW CHECKS:\n";
$createChecks = [
    'Multiple select (name="category[]")' => strpos($createContent, 'name="category[]"') !== false,
    'Multiple attribute' => strpos($createContent, 'multiple') !== false,
    'loadCategories function' => strpos($createContent, 'function loadCategories') !== false,
    'AJAX URL /ppos/categories/' => strpos($createContent, '/ppos/categories/') !== false,
    'select2:select event' => strpos($createContent, 'select2:select') !== false,
    'setTimeout for Select2' => strpos($createContent, 'setTimeout') !== false,
    'Console logging' => strpos($createContent, 'console.log') !== false,
    'Error handler' => strpos($createContent, 'error: function') !== false,
    'Multiple categories text' => strpos($createContent, 'one or more') !== false || strpos($createContent, 'multiple') !== false,
    'closeOnSelect: false' => strpos($createContent, 'closeOnSelect: false') !== false,
];

foreach ($createChecks as $name => $result) {
    echo ($result ? "  ✅" : "  ❌") . " {$name}\n";
}

echo "\nEDIT VIEW CHECKS:\n";
$editChecks = [
    'Single select (name="category")' => strpos($editContent, 'name="category"') !== false && strpos($editContent, 'name="category[]"') === false,
    'NO Multiple attribute' => strpos($editContent, 'multiple') === false,
    'loadCategories function' => strpos($editContent, 'function loadCategories') !== false,
    'AJAX URL /ppos/categories/' => strpos($editContent, '/ppos/categories/') !== false,
    'select2:select event' => strpos($editContent, 'select2:select') !== false,
    'setTimeout for Select2' => strpos($editContent, 'setTimeout') !== false,
    'Console logging' => strpos($editContent, 'console.log') !== false,
    'Error handler' => strpos($editContent, 'error: function') !== false,
    'Selected category preservation' => strpos($editContent, 'selectedCategory') !== false,
    'Current PPO category' => strpos($editContent, '$ppo->category') !== false,
];

foreach ($editChecks as $name => $result) {
    echo ($result ? "  ✅" : "  ❌") . " {$name}\n";
}
echo "\n";

// Test 2: JavaScript Structure
echo "2️⃣ JAVASCRIPT STRUCTURE:\n";
echo "--------------------------------------\n";

echo "CREATE page JavaScript:\n";
preg_match('/function loadCategories\(([^)]+)\)/', $createContent, $createParams);
echo "  - loadCategories parameters: " . ($createParams[1] ?? 'NOT FOUND') . "\n";

$createHasMultipleLogic = strpos($createContent, 'allowClear: true') !== false;
echo "  - Multiple selection logic: " . ($createHasMultipleLogic ? "✅ YES" : "❌ NO") . "\n";

echo "\nEDIT page JavaScript:\n";
preg_match('/function loadCategories\(([^)]+)\)/', $editContent, $editParams);
echo "  - loadCategories parameters: " . ($editParams[1] ?? 'NOT FOUND') . "\n";

$editHasSelectedCategory = strpos($editContent, 'selectedCategory') !== false;
echo "  - Selected category parameter: " . ($editHasSelectedCategory ? "✅ YES" : "❌ NO") . "\n";

echo "\n";

// Test 3: Functionality Differences
echo "3️⃣ FUNCTIONALITY DIFFERENCES:\n";
echo "--------------------------------------\n";

echo "Feature                          | CREATE    | EDIT\n";
echo "----------------------------------|-----------|----------\n";
echo "Multiple category selection      | " . (strpos($createContent, 'multiple') !== false ? "✅ YES    " : "❌ NO     ") . " | " . (strpos($editContent, 'multiple') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "AJAX category loading            | " . (strpos($createContent, '/ppos/categories/') !== false ? "✅ YES    " : "❌ NO     ") . " | " . (strpos($editContent, '/ppos/categories/') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "Preserve selected category       | " . (strpos($createContent, 'selectedCategory') !== false ? "✅ YES    " : "❌ NO     ") . " | " . (strpos($editContent, 'selectedCategory') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "Console debugging                | " . (strpos($createContent, 'console.log') !== false ? "✅ YES    " : "❌ NO     ") . " | " . (strpos($editContent, 'console.log') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "Error handling                   | " . (strpos($createContent, 'error: function') !== false ? "✅ YES    " : "❌ NO     ") . " | " . (strpos($editContent, 'error: function') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "Select2 re-initialization        | " . (strpos($createContent, '.select2(') !== false ? "✅ YES    " : "❌ NO     ") . " | " . (strpos($editContent, '.select2(') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "\n";

// Test 4: Sample Data Check
echo "4️⃣ SAMPLE DATA FOR TESTING:\n";
echo "--------------------------------------\n";

$projects = Project::all();
echo "Projects available: " . $projects->count() . "\n";
foreach ($projects as $project) {
    echo "  - ID: {$project->id}, PR: {$project->pr_number}, Name: {$project->name}\n";
    $catCount = Pepo::where('pr_number', $project->id)->count();
    echo "    └─ Categories: {$catCount}\n";
}

echo "\nExisting PPOS records: " . Ppos::count() . "\n";
$ppos = Ppos::with(['project', 'pepo'])->take(3)->get();
foreach ($ppos as $ppo) {
    echo "  - ID: {$ppo->id}, PR: {$ppo->pr_number}, Category: " .
         ($ppo->pepo ? $ppo->pepo->category : 'N/A') .
         ", PO: {$ppo->po_number}\n";
}

echo "\n";

// Test 5: Consistency Check
echo "5️⃣ CONSISTENCY CHECK:\n";
echo "--------------------------------------\n";

$issues = [];

// Check if both use same AJAX URL pattern
$createAjaxUrl = strpos($createContent, '/ppos/categories/${prNumber}') !== false;
$editAjaxUrl = strpos($editContent, '/ppos/categories/${prNumber}') !== false;
if ($createAjaxUrl && $editAjaxUrl) {
    echo "✅ Both use same AJAX URL pattern\n";
} else {
    echo "❌ AJAX URL pattern mismatch\n";
    $issues[] = "AJAX URL pattern inconsistent";
}

// Check if both have error handling
$createError = strpos($createContent, 'error: function') !== false;
$editError = strpos($editContent, 'error: function') !== false;
if ($createError && $editError) {
    echo "✅ Both have error handling\n";
} else {
    echo "❌ Error handling missing in one page\n";
    $issues[] = "Error handling inconsistent";
}

// Check if both load categories dynamically
$createDynamic = strpos($createContent, 'loadCategories') !== false;
$editDynamic = strpos($editContent, 'loadCategories') !== false;
if ($createDynamic && $editDynamic) {
    echo "✅ Both load categories dynamically\n";
} else {
    echo "❌ Dynamic loading inconsistent\n";
    $issues[] = "Dynamic loading inconsistent";
}

// Check console logging
$createConsole = substr_count($createContent, 'console.log') >= 5;
$editConsole = substr_count($editContent, 'console.log') >= 5;
if ($createConsole && $editConsole) {
    echo "✅ Both have adequate console logging\n";
} else {
    echo "⚠️ Console logging could be improved\n";
}

echo "\n";

// Final Summary
echo "========================================\n";
echo "📊 SUMMARY:\n";
echo "========================================\n";

if (empty($issues)) {
    echo "✅ All consistency checks passed!\n\n";
} else {
    echo "⚠️ Issues found:\n";
    foreach ($issues as $issue) {
        echo "  - {$issue}\n";
    }
    echo "\n";
}

echo "KEY DIFFERENCES (By Design):\n";
echo "  CREATE page:\n";
echo "    - Multiple category selection (category[])\n";
echo "    - Creates multiple PPOS records\n";
echo "    - closeOnSelect: false for multi-select\n";
echo "\n";
echo "  EDIT page:\n";
echo "    - Single category selection (category)\n";
echo "    - Updates one PPOS record\n";
echo "    - Preserves selected category on load\n";
echo "\n";

echo "🧪 TESTING STEPS:\n";
echo "--------------------------------------\n";
echo "CREATE PAGE (http://mdsjedpr.test/ppos/create):\n";
echo "  1. Open page and press F12\n";
echo "  2. Select PR Number from dropdown\n";
echo "  3. Check Console for: '📡 Loading categories'\n";
echo "  4. Verify categories appear\n";
echo "  5. Select MULTIPLE categories\n";
echo "  6. Submit and verify multiple records created\n";
echo "\n";
echo "EDIT PAGE (http://mdsjedpr.test/ppos/{id}/edit):\n";
echo "  1. Open existing PPOS record for editing\n";
echo "  2. Press F12 to open Console\n";
echo "  3. Check Console for: '✅ PPOS Edit page loaded'\n";
echo "  4. Verify current category is selected\n";
echo "  5. Change PR Number and verify categories reload\n";
echo "  6. Select new category and save\n";
echo "========================================\n";
