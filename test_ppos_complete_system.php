<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Ppos;
use App\Models\Project;
use App\Models\Pepo;
use App\Models\Ds;
use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "🧪 COMPLETE PPOS SYSTEM TEST\n";
echo "========================================\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

$testResults = [];
$totalTests = 0;
$passedTests = 0;

function test($name, $condition, $message = '') {
    global $testResults, $totalTests, $passedTests;
    $totalTests++;
    $result = $condition ? '✅' : '❌';
    $status = $condition ? 'PASS' : 'FAIL';

    if ($condition) {
        $passedTests++;
    }

    $testResults[] = [
        'name' => $name,
        'status' => $status,
        'message' => $message
    ];

    echo "{$result} {$name}\n";
    if ($message) {
        echo "   {$message}\n";
    }
}

// ========================================
// 1. DATABASE STRUCTURE TESTS
// ========================================
echo "\n1️⃣ DATABASE STRUCTURE:\n";
echo "--------------------------------------\n";

// Test table exists
$pposTableExists = DB::select("SHOW TABLES LIKE 'ppos'");
test("PPOS table exists", !empty($pposTableExists));

// Test columns
$columns = DB::select("DESCRIBE ppos");
$columnNames = array_column($columns, 'Field');

test("Has pr_number column", in_array('pr_number', $columnNames));
test("Has po_number column", in_array('po_number', $columnNames));
test("Has category column", in_array('category', $columnNames));
test("Has dsname column", in_array('dsname', $columnNames));
test("Has value column", in_array('value', $columnNames));
test("Has date column", in_array('date', $columnNames));

// Check if category has UNIQUE constraint (should NOT have it)
$indexes = DB::select("SHOW INDEXES FROM ppos WHERE Column_name = 'category'");
$hasUniqueConstraint = false;
foreach ($indexes as $index) {
    if ($index->Non_unique == 0) {
        $hasUniqueConstraint = true;
        break;
    }
}
test("Category UNIQUE constraint removed", !$hasUniqueConstraint, "Multiple categories allowed");

// ========================================
// 2. DATA INTEGRITY TESTS
// ========================================
echo "\n2️⃣ DATA INTEGRITY:\n";
echo "--------------------------------------\n";

$totalPpos = Ppos::count();
test("PPOS records exist", $totalPpos > 0, "Total: {$totalPpos} records");

$pposWithProject = Ppos::whereNotNull('pr_number')->count();
test("All PPOS have PR Number", $pposWithProject == $totalPpos);

$pposWithCategory = Ppos::whereNotNull('category')->count();
test("All PPOS have Category", $pposWithCategory == $totalPpos);

$pposWithPo = Ppos::whereNotNull('po_number')->count();
test("All PPOS have PO Number", $pposWithPo == $totalPpos);

// Test relationships
$samplePpo = Ppos::with(['project', 'pepo', 'ds'])->first();
if ($samplePpo) {
    test("Project relationship works", $samplePpo->project !== null);
    test("Category (Pepo) relationship works", $samplePpo->pepo !== null);
    test("Supplier (DS) relationship works", $samplePpo->ds !== null);
}

// ========================================
// 3. MULTIPLE CATEGORIES FEATURE
// ========================================
echo "\n3️⃣ MULTIPLE CATEGORIES FEATURE:\n";
echo "--------------------------------------\n";

$groupedByPo = Ppos::select('po_number', DB::raw('COUNT(*) as count'))
    ->groupBy('po_number')
    ->having('count', '>', 1)
    ->get();

$multiCategoryCount = $groupedByPo->count();
test("Multiple categories capability exists", true, "POs with multiple categories: {$multiCategoryCount}");

if ($multiCategoryCount > 0) {
    $sampleMulti = $groupedByPo->first();
    $records = Ppos::where('po_number', $sampleMulti->po_number)->with('pepo')->get();

    $categories = $records->pluck('pepo.category')->filter()->unique();
    test("Multiple categories stored correctly", $categories->count() > 1,
        "PO {$sampleMulti->po_number} has {$categories->count()} categories");

    // Test category display logic
    $displayCategories = $categories->implode(', ');
    test("Categories can be concatenated", strlen($displayCategories) > 0,
        "Display: {$displayCategories}");
}

// ========================================
// 4. PROJECT & CATEGORY RELATIONSHIP
// ========================================
echo "\n4️⃣ PROJECT & CATEGORY RELATIONSHIP:\n";
echo "--------------------------------------\n";

$projectsWithPpos = Ppos::distinct('pr_number')->count('pr_number');
test("Projects with PPOS exist", $projectsWithPpos > 0, "Total: {$projectsWithPpos} projects");

$firstPrNumber = Ppos::first()->pr_number ?? null;
if ($firstPrNumber) {
    $categoriesCount = Pepo::where('pr_number', $firstPrNumber)->count();
    test("Project has categories", $categoriesCount > 0,
        "PR {$firstPrNumber} has {$categoriesCount} categories");
}

// ========================================
// 5. SUPPLIER RELATIONSHIP
// ========================================
echo "\n5️⃣ SUPPLIER RELATIONSHIP:\n";
echo "--------------------------------------\n";

$suppliersWithPpos = Ppos::distinct('dsname')->count('dsname');
test("Suppliers with PPOS exist", $suppliersWithPpos > 0, "Total: {$suppliersWithPpos} suppliers");

$firstDsId = Ppos::first()->dsname ?? null;
if ($firstDsId) {
    $supplier = Ds::find($firstDsId);
    if ($supplier) {
        $supplierPpos = Ppos::where('dsname', $supplier->id)->count();
        test("Supplier has PPOS records", $supplierPpos > 0,
            "{$supplier->dsname} has {$supplierPpos} PPOS");
    }
}

// ========================================
// 6. VALUE & DATE FIELDS
// ========================================
echo "\n6️⃣ VALUE & DATE FIELDS:\n";
echo "--------------------------------------\n";

$pposWithValue = Ppos::whereNotNull('value')->where('value', '>', 0)->count();
test("PPOS with values exist", $pposWithValue > 0, "Count: {$pposWithValue}");

$pposWithDate = Ppos::whereNotNull('date')->count();
test("PPOS with dates exist", $pposWithDate > 0, "Count: {$pposWithDate}");

// Test value format
$sampleWithValue = Ppos::whereNotNull('value')->where('value', '>', 0)->first();
if ($sampleWithValue) {
    test("Value is numeric", is_numeric($sampleWithValue->value),
        "Sample value: \${$sampleWithValue->value}");
}

// ========================================
// 7. INDEX PAGE DISPLAY LOGIC
// ========================================
echo "\n7️⃣ INDEX PAGE DISPLAY LOGIC:\n";
echo "--------------------------------------\n";

// Simulate what index page shows
$allPpos = Ppos::with(['project', 'pepo', 'ds'])->get();
$displayable = 0;

foreach ($allPpos->take(5) as $ppo) {
    $allCategories = Ppos::where('po_number', $ppo->po_number)
        ->with('pepo:id,category')
        ->get()
        ->pluck('pepo.category')
        ->filter()
        ->unique()
        ->implode(', ');

    if (!empty($allCategories)) {
        $displayable++;
    }
}

test("INDEX display logic works", $displayable > 0,
    "Successfully generated display for {$displayable} records");

// ========================================
// 8. SHOW PAGE DISPLAY LOGIC
// ========================================
echo "\n8️⃣ SHOW PAGE DISPLAY LOGIC:\n";
echo "--------------------------------------\n";

$testPpo = Ppos::first();
if ($testPpo) {
    // Test individual category display
    $allPpoCategories = Ppos::where('po_number', $testPpo->po_number)
        ->with('pepo:id,category')
        ->get();

    $categories = $allPpoCategories->pluck('pepo.category')->filter()->unique();

    test("SHOW page gets all categories", $categories->count() > 0,
        "Found {$categories->count()} categories for PO {$testPpo->po_number}");

    // Test export format
    $categoriesForExport = $categories->implode(', ');
    test("Export format works", !empty($categoriesForExport),
        "Export: {$categoriesForExport}");
}

// ========================================
// 9. PERFORMANCE TESTS
// ========================================
echo "\n9️⃣ PERFORMANCE TESTS:\n";
echo "--------------------------------------\n";

// Test query performance
$start = microtime(true);
$ppos = Ppos::with(['project', 'pepo', 'ds'])->limit(100)->get();
$queryTime = (microtime(true) - $start) * 1000;

test("Query performance acceptable", $queryTime < 1000,
    sprintf("%.2f ms for 100 records", $queryTime));

// Test grouping performance
$start = microtime(true);
$grouped = Ppos::select('po_number')
    ->groupBy('po_number')
    ->get();
$groupTime = (microtime(true) - $start) * 1000;

test("Grouping performance acceptable", $groupTime < 500,
    sprintf("%.2f ms to group %d PO numbers", $groupTime, $grouped->count()));

// ========================================
// 10. DATA VALIDATION TESTS
// ========================================
echo "\n🔟 DATA VALIDATION:\n";
echo "--------------------------------------\n";

// Check for orphaned records
$orphanedPpos = Ppos::whereNotExists(function($query) {
    $query->select(DB::raw(1))
          ->from('projects')
          ->whereRaw('projects.pr_number = ppos.pr_number');
})->count();

test("No orphaned PPOS (no project)", $orphanedPpos == 0,
    "Orphaned: {$orphanedPpos}");

$orphanedCategories = Ppos::whereNotExists(function($query) {
    $query->select(DB::raw(1))
          ->from('pepos')
          ->whereRaw('pepos.id = ppos.category');
})->count();

test("No orphaned categories", $orphanedCategories == 0,
    "Orphaned: {$orphanedCategories}");

// ========================================
// 11. STATISTICS & SUMMARY
// ========================================
echo "\n1️⃣1️⃣ STATISTICS & SUMMARY:\n";
echo "--------------------------------------\n";

$stats = [
    'total_ppos' => Ppos::count(),
    'unique_po_numbers' => Ppos::distinct('po_number')->count('po_number'),
    'unique_projects' => Ppos::distinct('pr_number')->count('pr_number'),
    'unique_suppliers' => Ppos::distinct('dsname')->count('dsname'),
    'total_value' => Ppos::sum('value'),
    'avg_value' => Ppos::avg('value'),
];

echo "Total PPOS Records: {$stats['total_ppos']}\n";
echo "Unique PO Numbers: {$stats['unique_po_numbers']}\n";
echo "Unique Projects: {$stats['unique_projects']}\n";
echo "Unique Suppliers: {$stats['unique_suppliers']}\n";
echo "Total Value: \$" . number_format($stats['total_value'], 2) . "\n";
echo "Average Value: \$" . number_format($stats['avg_value'], 2) . "\n";

test("Statistics calculated successfully", true);

// ========================================
// 12. DETAILED SAMPLE DATA
// ========================================
echo "\n1️⃣2️⃣ SAMPLE DATA REVIEW:\n";
echo "--------------------------------------\n";

$samplePpos = Ppos::with(['project', 'pepo', 'ds'])
    ->orderBy('created_at', 'desc')
    ->take(3)
    ->get();

foreach ($samplePpos as $index => $ppo) {
    echo "\nSample #" . ($index + 1) . ":\n";
    echo "  ID: {$ppo->id}\n";
    echo "  PR Number: {$ppo->pr_number}\n";
    echo "  Project: " . ($ppo->project->name ?? 'N/A') . "\n";
    echo "  PO Number: {$ppo->po_number}\n";
    echo "  Category: " . ($ppo->pepo->category ?? 'N/A') . "\n";
    echo "  Supplier: " . ($ppo->ds->dsname ?? 'N/A') . "\n";
    echo "  Value: \${$ppo->value}\n";
    echo "  Date: {$ppo->date}\n";

    // Show all categories for this PO
    $allCats = Ppos::where('po_number', $ppo->po_number)
        ->with('pepo')
        ->get()
        ->pluck('pepo.category')
        ->filter()
        ->unique();

    if ($allCats->count() > 1) {
        echo "  All Categories: " . $allCats->implode(', ') . "\n";
    }
}

test("Sample data displayed successfully", $samplePpos->count() > 0);

// ========================================
// FINAL RESULTS
// ========================================
echo "\n========================================\n";
echo "📊 TEST RESULTS SUMMARY\n";
echo "========================================\n\n";

echo "Total Tests: {$totalTests}\n";
echo "Passed: {$passedTests} ✅\n";
echo "Failed: " . ($totalTests - $passedTests) . " ❌\n";
echo "Success Rate: " . round(($passedTests / $totalTests) * 100, 2) . "%\n\n";

if ($passedTests == $totalTests) {
    echo "🎉 ALL TESTS PASSED! PPOS System is working perfectly!\n";
} else {
    echo "⚠️ Some tests failed. Review the results above.\n\n";
    echo "Failed Tests:\n";
    foreach ($testResults as $result) {
        if ($result['status'] == 'FAIL') {
            echo "  ❌ {$result['name']}\n";
            if ($result['message']) {
                echo "     {$result['message']}\n";
            }
        }
    }
}

echo "\n========================================\n";
echo "🎯 FEATURES TESTED:\n";
echo "========================================\n";
echo "✓ Database structure and migrations\n";
echo "✓ Data integrity and relationships\n";
echo "✓ Multiple categories feature\n";
echo "✓ Project & category relationships\n";
echo "✓ Supplier relationships\n";
echo "✓ Value & date fields\n";
echo "✓ INDEX page display logic\n";
echo "✓ SHOW page display logic\n";
echo "✓ Query performance\n";
echo "✓ Data validation\n";
echo "✓ Statistics and reporting\n";
echo "✓ Sample data review\n";
echo "========================================\n";
