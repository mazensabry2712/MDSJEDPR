<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice System Debug</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 0 0 20px 0;
        }
        h2 {
            background: #4a5568;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .success {
            color: #22c55e;
            font-weight: bold;
        }
        .error {
            color: #ef4444;
            font-weight: bold;
        }
        .warning {
            color: #f59e0b;
            font-weight: bold;
        }
        .info {
            color: #3b82f6;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #f3f4f6;
            font-weight: bold;
        }
        code {
            background: #1f2937;
            color: #10b981;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        .dropdown-test {
            margin-top: 15px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 5px;
            border: 2px solid #d1d5db;
        }
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 Invoice System Debug Report</h1>";
echo "<p style='color: #6b7280;'>Generated: " . date('Y-m-d H:i:s') . "</p>";

try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    // Test 1: Environment
    echo "<div class='card'>";
    echo "<h2>1️⃣ Environment Information</h2>";
    echo "<table>";
    echo "<tr><th>Setting</th><th>Value</th></tr>";
    echo "<tr><td>PHP Version</td><td>" . phpversion() . "</td></tr>";
    echo "<tr><td>Laravel Version</td><td>" . app()->version() . "</td></tr>";
    echo "<tr><td>APP_ENV</td><td><code>" . config('app.env') . "</code></td></tr>";
    echo "<tr><td>APP_DEBUG</td><td><code>" . (config('app.debug') ? 'true' : 'false') . "</code></td></tr>";
    echo "<tr><td>APP_URL</td><td><code>" . config('app.url') . "</code></td></tr>";
    echo "<tr><td>Cache Driver</td><td><code>" . config('cache.default') . "</code></td></tr>";
    echo "</table>";
    echo "</div>";

    // Test 2: Database Connection
    echo "<div class='card'>";
    echo "<h2>2️⃣ Database Connection</h2>";
    try {
        DB::connection()->getPdo();
        $dbName = DB::connection()->getDatabaseName();
        $driver = DB::connection()->getDriverName();
        echo "<p class='success'>✅ Connected to database: <strong>{$dbName}</strong> (Driver: {$driver})</p>";

        // Check tables
        $tables = DB::select('SHOW TABLES');
        echo "<p class='info'>ℹ️ Total tables: " . count($tables) . "</p>";
    } catch (Exception $e) {
        echo "<p class='error'>❌ Database Connection Failed!</p>";
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    // Test 3: Projects Data
    echo "<div class='card'>";
    echo "<h2>3️⃣ Projects Data</h2>";
    try {
        $projects = App\Models\Project::all();
        $count = $projects->count();

        if ($count === 0) {
            echo "<p class='error'>❌ NO PROJECTS FOUND IN DATABASE!</p>";
            echo "<p>This is why the dropdown shows 'No results found'</p>";
            echo "<p><strong>Solution:</strong> Add projects to the database first!</p>";
        } else {
            echo "<p class='success'>✅ Found {$count} project(s)</p>";
            echo "<table>";
            echo "<tr><th>ID</th><th>PR Number</th><th>Name</th><th>Value</th></tr>";
            foreach ($projects as $p) {
                echo "<tr>";
                echo "<td>{$p->id}</td>";
                echo "<td><strong>{$p->pr_number}</strong></td>";
                echo "<td>{$p->name}</td>";
                echo "<td>" . ($p->value ?? 'N/A') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ Error loading projects: " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    // Test 4: Cache
    echo "<div class='card'>";
    echo "<h2>4️⃣ Cache Status</h2>";

    // Check current cache
    $cachedProjects = Cache::get('projects_list');
    if ($cachedProjects) {
        echo "<p class='warning'>⚠️ Cache exists: " . $cachedProjects->count() . " projects cached</p>";
        if ($cachedProjects->isEmpty()) {
            echo "<p class='error'>❌ Cache contains EMPTY data! This is the problem!</p>";
            echo "<p><strong>Solution:</strong> Clear cache with: <code>php artisan cache:clear</code></p>";
        }
    } else {
        echo "<p class='info'>ℹ️ No cache found (will be created on first request)</p>";
    }

    // Clear and rebuild cache
    Cache::forget('projects_list');
    $freshCache = Cache::remember('projects_list', 3600, function () {
        return App\Models\Project::select('id', 'pr_number', 'name')->get();
    });

    echo "<p class='success'>✅ Cache rebuilt: " . $freshCache->count() . " projects</p>";
    echo "</div>";

    // Test 5: Controller Simulation
    echo "<div class='card'>";
    echo "<h2>5️⃣ Controller Simulation</h2>";
    try {
        // Simulate what InvoicesController@create does
        $pr_number_idd = Cache::remember('projects_list', 3600, function () {
            return App\Models\Project::select('id', 'pr_number', 'name')->get();
        });

        echo "<p class='success'>✅ Controller would receive: {$pr_number_idd->count()} projects</p>";
        echo "<p>Variable name: <code>\$pr_number_idd</code></p>";

        if ($pr_number_idd->isEmpty()) {
            echo "<p class='error'>❌ Controller receives EMPTY collection!</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ Controller simulation failed: " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    // Test 6: Dropdown HTML
    echo "<div class='card'>";
    echo "<h2>6️⃣ Dropdown HTML Test</h2>";
    $dropdownProjects = App\Models\Project::select('id', 'pr_number', 'name')->get();

    echo "<p>This is what the dropdown should look like:</p>";
    echo "<div class='dropdown-test'>";
    echo "<label><strong>PR Number:</strong></label>";
    echo "<select name='pr_number' id='pr_number' class='form-control'>";
    echo "<option value='' selected disabled>Select PR Number</option>";

    if ($dropdownProjects->isEmpty()) {
        echo "<!-- NO OPTIONS BECAUSE NO PROJECTS -->";
    } else {
        foreach ($dropdownProjects as $pr) {
            echo "<option value='{$pr->id}' data-project-name='{$pr->name}'>{$pr->pr_number}</option>";
        }
    }
    echo "</select>";
    echo "</div>";

    if ($dropdownProjects->isEmpty()) {
        echo "<p class='error'>❌ Dropdown has NO options!</p>";
    } else {
        echo "<p class='success'>✅ Dropdown has {$dropdownProjects->count()} option(s)</p>";
    }
    echo "</div>";

    // Test 7: File Permissions
    echo "<div class='card'>";
    echo "<h2>7️⃣ File Permissions</h2>";
    $paths = [
        'storage/framework/cache' => storage_path('framework/cache'),
        'storage/logs' => storage_path('logs'),
        'bootstrap/cache' => base_path('bootstrap/cache'),
    ];

    echo "<table>";
    echo "<tr><th>Path</th><th>Writable?</th></tr>";
    foreach ($paths as $name => $path) {
        $writable = is_writable($path);
        $status = $writable ? "<span class='success'>✅ YES</span>" : "<span class='error'>❌ NO</span>";
        echo "<tr><td>{$name}</td><td>{$status}</td></tr>";
    }
    echo "</table>";
    echo "</div>";

    // Test 8: Routes
    echo "<div class='card'>";
    echo "<h2>8️⃣ Invoice Routes</h2>";
    $routes = Illuminate\Support\Facades\Route::getRoutes();
    $invoiceRoutes = [];
    foreach ($routes as $route) {
        if (strpos($route->uri(), 'invoice') !== false) {
            $invoiceRoutes[] = [
                'uri' => $route->uri(),
                'methods' => implode(', ', $route->methods()),
                'action' => $route->getActionName()
            ];
        }
    }

    echo "<p class='success'>✅ Found " . count($invoiceRoutes) . " invoice routes</p>";
    echo "<table>";
    echo "<tr><th>Method</th><th>URI</th><th>Action</th></tr>";
    foreach ($invoiceRoutes as $r) {
        echo "<tr><td>{$r['methods']}</td><td>{$r['uri']}</td><td><code>" . basename($r['action']) . "</code></td></tr>";
    }
    echo "</table>";
    echo "</div>";

    // Test 9: Diagnosis
    echo "<div class='card'>";
    echo "<h2>9️⃣ Diagnosis & Solutions</h2>";

    $projectCount = App\Models\Project::count();

    if ($projectCount === 0) {
        echo "<div style='background:#fee2e2;padding:15px;border-radius:5px;border-left:4px solid #ef4444'>";
        echo "<p class='error'>🚨 PROBLEM IDENTIFIED: No projects in database!</p>";
        echo "<p><strong>This is why you see 'No results found'</strong></p>";
        echo "<p><strong>Solution:</strong></p>";
        echo "<ol>";
        echo "<li>Add projects to the database</li>";
        echo "<li>Or import existing projects if you have them</li>";
        echo "<li>Or check if database connection is correct</li>";
        echo "</ol>";
        echo "</div>";
    } else {
        echo "<div style='background:#d1fae5;padding:15px;border-radius:5px;border-left:4px solid #10b981'>";
        echo "<p class='success'>✅ System is working correctly locally!</p>";
        echo "<p>If still not working on Hostinger, try these steps:</p>";
        echo "<ol>";
        echo "<li>Run: <code>php artisan cache:clear</code></li>";
        echo "<li>Run: <code>php artisan config:clear</code></li>";
        echo "<li>Run: <code>php artisan view:clear</code></li>";
        echo "<li>Check file permissions: <code>chmod -R 775 storage</code></li>";
        echo "<li>Verify .env database credentials are correct</li>";
        echo "<li>Check browser console (F12) for JavaScript errors</li>";
        echo "<li>Ensure Select2 assets are uploaded to <code>public/assets/plugins/select2/</code></li>";
        echo "</ol>";
        echo "</div>";
    }
    echo "</div>";

    // Test 10: Quick Actions
    echo "<div class='card'>";
    echo "<h2>🔟 Quick Actions</h2>";
    echo "<p>Copy and paste these commands in your terminal:</p>";
    echo "<div style='background:#1f2937;color:#10b981;padding:15px;border-radius:5px;font-family:monospace;'>";
    echo "# Clear all caches<br>";
    echo "php artisan cache:clear<br>";
    echo "php artisan config:clear<br>";
    echo "php artisan view:clear<br>";
    echo "php artisan route:clear<br>";
    echo "<br># Fix permissions<br>";
    echo "chmod -R 775 storage<br>";
    echo "chmod -R 775 bootstrap/cache<br>";
    echo "<br># Check projects<br>";
    echo "php artisan tinker<br>";
    echo ">>> App\\Models\\Project::count()<br>";
    echo ">>> App\\Models\\Project::all()<br>";
    echo "</div>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='card'>";
    echo "<h2>❌ Fatal Error</h2>";
    echo "<p class='error'>Could not load Laravel application!</p>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p><strong>Possible causes:</strong></p>";
    echo "<ul>";
    echo "<li>vendor/ folder missing - run: <code>composer install</code></li>";
    echo "<li>.env file missing or incorrect</li>";
    echo "<li>File permissions issue</li>";
    echo "</ul>";
    echo "</div>";
}
?>

<div class="card" style="background:#f0f9ff;border:2px solid #3b82f6;">
    <h2 style="background:#3b82f6;">📞 Support Information</h2>
    <p><strong>Test Date:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
    <p><strong>Script Location:</strong> <?php echo __FILE__; ?></p>
    <p><strong>Document Root:</strong> <?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'; ?></p>
    <p><strong>Server Software:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></p>
</div>

</body>
</html>
