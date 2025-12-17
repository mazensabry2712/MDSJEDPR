<?php

// Test Profile Picture Upload Functionality

echo "<h2>Testing Profile Picture Upload System</h2>";

// Test 1: Check if storge directory exists
$storgePath = __DIR__ . '/storge';
echo "<h3>Test 1: Check storge directory</h3>";
if (is_dir($storgePath)) {
    echo "✅ storge directory exists: " . $storgePath . "<br>";
    echo "✅ Directory is writable: " . (is_writable($storgePath) ? 'Yes' : 'No') . "<br>";
} else {
    echo "❌ storge directory does NOT exist<br>";
}

// Test 2: Check database connection
echo "<h3>Test 2: Check database connection</h3>";
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

try {
    $connection = \Illuminate\Support\Facades\DB::connection();
    $connection->getPdo();
    echo "✅ Database connected successfully<br>";

    // Check if users table has profile_picture column
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
    if (in_array('profile_picture', $columns)) {
        echo "✅ 'profile_picture' column exists in users table<br>";
    } else {
        echo "❌ 'profile_picture' column NOT found in users table<br>";
    }
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
}

// Test 3: Check route exists
echo "<h3>Test 3: Check routes</h3>";
try {
    $routes = app('router')->getRoutes();
    $profileEditRoute = null;
    $profileUpdateRoute = null;

    foreach ($routes as $route) {
        if ($route->getName() === 'profile.edit') {
            $profileEditRoute = $route;
        }
        if ($route->getName() === 'profile.update') {
            $profileUpdateRoute = $route;
        }
    }

    if ($profileEditRoute) {
        echo "✅ Route 'profile.edit' exists: " . $profileEditRoute->uri() . "<br>";
    } else {
        echo "❌ Route 'profile.edit' NOT found<br>";
    }

    if ($profileUpdateRoute) {
        echo "✅ Route 'profile.update' exists: " . $profileUpdateRoute->uri() . "<br>";
    } else {
        echo "❌ Route 'profile.update' NOT found<br>";
    }
} catch (Exception $e) {
    echo "❌ Error checking routes: " . $e->getMessage() . "<br>";
}

// Test 4: Check files in storge
echo "<h3>Test 4: List files in storge directory</h3>";
$files = scandir($storgePath);
$imageFiles = array_filter($files, function($file) {
    return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
});

if (count($imageFiles) > 0) {
    echo "✅ Found " . count($imageFiles) . " image files in storge:<br>";
    foreach (array_slice($imageFiles, 0, 5) as $file) {
        echo "- " . $file . "<br>";
    }
} else {
    echo "⚠️ No image files found in storge directory<br>";
}

// Test 5: Check current user (if authenticated)
echo "<h3>Test 5: Check authentication</h3>";
try {
    if (auth()->check()) {
        $user = auth()->user();
        echo "✅ User authenticated: " . $user->name . "<br>";
        echo "✅ User email: " . $user->email . "<br>";
        echo "✅ Profile picture: " . ($user->profile_picture ?? 'Not set') . "<br>";

        if ($user->profile_picture) {
            $fullPath = $storgePath . '/' . $user->profile_picture;
            if (file_exists($fullPath)) {
                echo "✅ Profile picture file exists<br>";
                echo "✅ File size: " . filesize($fullPath) . " bytes<br>";
            } else {
                echo "❌ Profile picture file NOT found at: " . $fullPath . "<br>";
            }
        }
    } else {
        echo "⚠️ No user authenticated (please login first)<br>";
    }
} catch (Exception $e) {
    echo "❌ Error checking user: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h3>Summary</h3>";
echo "<p>If all tests pass (✅), the system should work correctly.</p>";
echo "<p><a href='/profile'>Go to Profile Page</a></p>";
