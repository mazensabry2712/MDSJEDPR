<?php
// Simple one-line test for Hostinger
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Projects: " . App\Models\Project::count() . "<br>";
echo "Cache: " . (Illuminate\Support\Facades\Cache::get('projects_list') ? Illuminate\Support\Facades\Cache::get('projects_list')->count() : 'NULL') . "<br>";

// Clear cache
Illuminate\Support\Facades\Cache::forget('projects_list');
echo "Cache cleared!<br>";

// Rebuild
$new = Illuminate\Support\Facades\Cache::remember('projects_list', 3600, function () {
    return App\Models\Project::select('id', 'pr_number', 'name')->get();
});

echo "Rebuilt cache: " . $new->count() . " projects<br>";

if ($new->count() > 0) {
    echo "<h3>✅ FIX SUCCESSFUL! Refresh invoice page now!</h3>";
} else {
    echo "<h3>❌ Still empty - check database!</h3>";
}
