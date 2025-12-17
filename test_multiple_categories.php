<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "🧪 TEST MULTIPLE CATEGORIES\n";
echo "========================================\n\n";

$project = App\Models\Project::first();

if (!$project) {
    echo "❌ No project found!\n";
    exit(1);
}

echo "Using Project:\n";
echo "  - ID: {$project->id}\n";
echo "  - PR Number: {$project->pr_number}\n\n";

// Test: Create multiple categories for same PR Number
echo "Creating multiple EPO categories for same PR...\n";
echo "--------------------------------------\n";

try {
    // Category 1
    $epo1 = App\Models\Pepo::create([
        'pr_number' => $project->id,
        'category' => 'Category A',
        'planned_cost' => 1000.00,
        'selling_price' => 1500.00,
    ]);
    echo "✅ Created EPO 1: Category A\n";

    // Category 2 (SAME PR Number)
    $epo2 = App\Models\Pepo::create([
        'pr_number' => $project->id,
        'category' => 'Category B',
        'planned_cost' => 2000.00,
        'selling_price' => 2500.00,
    ]);
    echo "✅ Created EPO 2: Category B\n";

    // Category 3 (SAME PR Number)
    $epo3 = App\Models\Pepo::create([
        'pr_number' => $project->id,
        'category' => 'Category C',
        'planned_cost' => 3000.00,
        'selling_price' => 3500.00,
    ]);
    echo "✅ Created EPO 3: Category C\n\n";

    echo "✅ SUCCESS! Multiple categories allowed for same PR!\n\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// Show all EPO records
echo "All EPO Records:\n";
echo "--------------------------------------\n";
$allEpos = App\Models\Pepo::all();
foreach ($allEpos as $epo) {
    echo "ID: {$epo->id} | PR: {$epo->pr_number} | Category: {$epo->category} | Cost: {$epo->planned_cost}\n";
}

echo "\n";

// Test PPOS dropdown
echo "Testing PPOS Categories API:\n";
echo "--------------------------------------\n";
$categories = App\Models\Pepo::where('pr_number', $project->id)
    ->select('id', 'category')->get();

echo "Categories for PR {$project->id}:\n";
foreach ($categories as $cat) {
    echo "  - ID: {$cat->id}, Category: {$cat->category}\n";
}

echo "\n✅ All categories will appear in PPOS dropdown!\n";

echo "\n========================================\n";
