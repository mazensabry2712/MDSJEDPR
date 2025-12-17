<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Ppos;

echo "========================================\n";
echo "🧪 PPOS DISPLAY - MULTIPLE CATEGORIES TEST\n";
echo "========================================\n\n";

// Test 1: Check existing PPOS records
echo "1️⃣ EXISTING PPOS RECORDS:\n";
echo "--------------------------------------\n";

$ppos = Ppos::with(['project', 'pepo', 'ds'])->get();
$groupedByPo = $ppos->groupBy('po_number');

echo "Total PPOS records: " . $ppos->count() . "\n";
echo "Unique PO Numbers: " . $groupedByPo->count() . "\n\n";

foreach ($groupedByPo as $poNumber => $records) {
    echo "PO Number: {$poNumber}\n";
    echo "  Records: " . $records->count() . "\n";
    echo "  Categories:\n";
    
    foreach ($records as $record) {
        $category = $record->pepo ? $record->pepo->category : 'N/A';
        echo "    - {$category} (Record ID: {$record->id})\n";
    }
    
    // Show what will be displayed
    $allCategories = $records->pluck('pepo.category')->filter()->unique();
    echo "  Display: " . $allCategories->implode(', ') . "\n";
    echo "\n";
}

// Test 2: Index page display simulation
echo "2️⃣ INDEX PAGE DISPLAY SIMULATION:\n";
echo "--------------------------------------\n";

foreach ($groupedByPo->take(3) as $poNumber => $records) {
    $firstRecord = $records->first();
    
    // Simulate what index.blade.php will show
    $displayCategories = Ppos::where('po_number', $poNumber)
        ->with('pepo:id,category')
        ->get()
        ->pluck('pepo.category')
        ->filter()
        ->unique()
        ->implode(', ');
    
    echo "Row for PO: {$poNumber}\n";
    echo "  PR Number: " . ($firstRecord->project->pr_number ?? 'N/A') . "\n";
    echo "  Project: " . ($firstRecord->project->name ?? 'N/A') . "\n";
    echo "  PO Number: {$poNumber}\n";
    echo "  Categories: {$displayCategories}\n";
    echo "  Supplier: " . ($firstRecord->ds->dsname ?? 'N/A') . "\n";
    echo "\n";
}

// Test 3: Show page display simulation
echo "3️⃣ SHOW PAGE DISPLAY SIMULATION:\n";
echo "--------------------------------------\n";

$samplePpo = $ppos->first();
if ($samplePpo) {
    $allPpoCategories = Ppos::where('po_number', $samplePpo->po_number)
        ->with('pepo:id,category')
        ->get();
    
    $categories = $allPpoCategories->pluck('pepo.category')->filter()->unique();
    
    echo "Viewing PPO ID: {$samplePpo->id}\n";
    echo "PO Number: {$samplePpo->po_number}\n";
    echo "Categories:\n";
    
    if ($categories->count() > 0) {
        foreach ($categories as $category) {
            echo "  [Badge] {$category}\n";
        }
        echo "Total: {$categories->count()} categor" . ($categories->count() > 1 ? 'ies' : 'y') . "\n";
    } else {
        echo "  N/A\n";
    }
}

echo "\n";

// Test 4: Export data simulation
echo "4️⃣ EXPORT DATA SIMULATION:\n";
echo "--------------------------------------\n";

if ($samplePpo) {
    $categoriesForExport = Ppos::where('po_number', $samplePpo->po_number)
        ->with('pepo:id,category')
        ->get()
        ->pluck('pepo.category')
        ->filter()
        ->unique()
        ->implode(', ');
    
    echo "PDF Export for PO: {$samplePpo->po_number}\n";
    echo "  Categories field: {$categoriesForExport}\n\n";
    
    echo "Excel Export for PO: {$samplePpo->po_number}\n";
    echo "  Categories field: {$categoriesForExport}\n\n";
    
    echo "CSV Export for PO: {$samplePpo->po_number}\n";
    echo "  Categories field: \"{$categoriesForExport}\"\n";
}

echo "\n";

// Test 5: Check view files
echo "5️⃣ VIEW FILES CHECK:\n";
echo "--------------------------------------\n";

$indexPath = resource_path('views/dashboard/PPOs/index.blade.php');
$showPath = resource_path('views/dashboard/PPOs/show.blade.php');

$indexContent = file_get_contents($indexPath);
$showContent = file_get_contents($showPath);

echo "INDEX VIEW:\n";
$indexChecks = [
    'Gets all categories by PO' => strpos($indexContent, "where('po_number'") !== false,
    'Uses pluck for categories' => strpos($indexContent, 'pluck') !== false,
    'Filters and unique' => strpos($indexContent, 'filter()->unique()') !== false,
    'Displays as badge' => strpos($indexContent, 'badge') !== false,
];

foreach ($indexChecks as $name => $result) {
    echo ($result ? "  ✅" : "  ❌") . " {$name}\n";
}

echo "\nSHOW VIEW:\n";
$showChecks = [
    'Gets all categories by PO' => strpos($showContent, "where('po_number'") !== false,
    'Uses foreach to display' => strpos($showContent, '@foreach($categories') !== false,
    'Shows count' => strpos($showContent, 'count()') !== false,
    'Updated PDF export' => strpos($showContent, 'Categories') !== false,
    'Badge styling' => strpos($showContent, 'badge badge-primary') !== false,
];

foreach ($showChecks as $name => $result) {
    echo ($result ? "  ✅" : "  ❌") . " {$name}\n";
}

echo "\n";

// Summary
echo "========================================\n";
echo "📊 SUMMARY:\n";
echo "========================================\n";

$totalPoNumbers = $groupedByPo->count();
$multiCategoryPOs = $groupedByPo->filter(function($records) {
    return $records->count() > 1;
})->count();

echo "Total PO Numbers: {$totalPoNumbers}\n";
echo "POs with multiple categories: {$multiCategoryPOs}\n";
echo "POs with single category: " . ($totalPoNumbers - $multiCategoryPOs) . "\n\n";

if ($multiCategoryPOs > 0) {
    echo "✅ Multiple categories feature is being utilized!\n\n";
} else {
    echo "⚠️ No POs with multiple categories yet.\n";
    echo "   Create/Edit a PPO and select multiple categories to test.\n\n";
}

echo "🎯 WHAT'S DISPLAYED:\n";
echo "--------------------------------------\n";
echo "INDEX page:\n";
echo "  - Shows all categories for each PO Number\n";
echo "  - Categories displayed as: 'Category A, Category B, Category C'\n";
echo "  - In a badge with tooltip\n\n";

echo "SHOW page:\n";
echo "  - Shows all categories as separate badges\n";
echo "  - Each category in its own colored badge\n";
echo "  - Shows count: 'X categories'\n";
echo "  - Exports include all categories\n\n";

echo "🧪 TEST IT:\n";
echo "--------------------------------------\n";
echo "1. Visit: http://mdsjedpr.test/ppos\n";
echo "2. Look at the Category column\n";
echo "3. Click 'Show' on any PPO\n";
echo "4. Check the Categories section\n";
echo "5. Try PDF/Excel/CSV export\n";
echo "========================================\n";
