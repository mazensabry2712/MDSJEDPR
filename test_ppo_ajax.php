<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing PPO Categories AJAX ===\n\n";

// Get first project
$project = App\Models\Project::first();
if (!$project) {
    echo "❌ No projects found!\n";
    exit;
}

echo "✅ Project ID: {$project->id}\n";
echo "✅ Project PR Number: {$project->pr_number}\n";
echo "✅ Project Name: {$project->name}\n\n";

// Test the controller method logic
$categories = App\Models\Pepo::where('pr_number', $project->id)
    ->select('id', 'category')
    ->get();

echo "📊 Found {$categories->count()} categories\n\n";

if ($categories->count() > 0) {
    echo "Categories:\n";
    foreach ($categories as $cat) {
        echo "  - ID: {$cat->id}, Category: {$cat->category}\n";
    }

    echo "\n✅ JSON Response would be:\n";
    echo json_encode([
        'success' => true,
        'categories' => $categories
    ], JSON_PRETTY_PRINT);
} else {
    echo "⚠️ No categories found for this project!\n";
    echo "This means the PEPO table has no records with pr_number = {$project->id}\n";
}
