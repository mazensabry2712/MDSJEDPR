<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "🔍 COMPLETE INVOICE SYSTEM TEST\n";
echo "========================================\n\n";

// Test 1: Check Projects Data
echo "1️⃣ PROJECTS DATA CHECK:\n";
echo "--------------------------------------\n";
$projects = App\Models\Project::all();
echo "Total Projects: " . $projects->count() . "\n";
if ($projects->isEmpty()) {
    echo "❌ NO PROJECTS FOUND! This is why dropdown is empty!\n\n";
} else {
    echo "✅ Projects available:\n";
    foreach ($projects as $p) {
        echo "  - ID: {$p->id} | PR: {$p->pr_number} | Name: {$p->name}\n";
    }
    echo "\n";
}

// Test 2: Check Controller Method
echo "2️⃣ CONTROLLER CREATE METHOD:\n";
echo "--------------------------------------\n";
try {
    $controller = new App\Http\Controllers\InvoicesController();
    $reflection = new ReflectionMethod($controller, 'create');
    echo "✅ create() method exists\n";
    
    // Simulate controller call
    $pr_number_idd = Cache::remember('projects_list', 3600, function () {
        return App\Models\Project::select('id', 'pr_number', 'name')->get();
    });
    
    echo "✅ projects_list cache: " . $pr_number_idd->count() . " projects\n";
    if ($pr_number_idd->isEmpty()) {
        echo "❌ Cache returned empty! No projects to show in dropdown!\n";
    } else {
        echo "✅ Cache has projects:\n";
        foreach ($pr_number_idd as $pr) {
            echo "  - {$pr->pr_number}\n";
        }
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// Test 3: Check View File
echo "3️⃣ VIEW FILE CHECK:\n";
echo "--------------------------------------\n";
$viewPath = resource_path('views/dashboard/invoice/create.blade.php');
if (file_exists($viewPath)) {
    echo "✅ View file exists\n";
    
    $viewContent = file_get_contents($viewPath);
    
    // Check for PR Number dropdown
    if (strpos($viewContent, 'name="pr_number"') !== false) {
        echo "✅ PR Number field exists\n";
        
        // Extract the dropdown section
        preg_match('/<select[^>]*name="pr_number"[^>]*>.*?<\/select>/s', $viewContent, $matches);
        if (!empty($matches)) {
            $dropdown = $matches[0];
            
            // Check for @foreach
            if (strpos($dropdown, '@foreach') !== false) {
                echo "✅ Uses @foreach loop\n";
                
                // Check variable name
                if (strpos($dropdown, '$pr_number_idd') !== false) {
                    echo "✅ Uses \$pr_number_idd variable\n";
                } else {
                    echo "❌ Variable name mismatch!\n";
                }
                
                // Check what is displayed
                if (strpos($dropdown, '{{ $pr_number_id->pr_number }}') !== false) {
                    echo "✅ Displays pr_number correctly\n";
                } else {
                    echo "⚠️  Check what is displayed in options\n";
                }
            } else {
                echo "❌ No @foreach found!\n";
            }
        }
    } else {
        echo "❌ PR Number field not found!\n";
    }
    echo "\n";
} else {
    echo "❌ View file not found at: {$viewPath}\n\n";
}

// Test 4: Route Check
echo "4️⃣ ROUTE CHECK:\n";
echo "--------------------------------------\n";
$routes = Illuminate\Support\Facades\Route::getRoutes();
$invoiceRoutes = [];
foreach ($routes as $route) {
    if (strpos($route->uri(), 'invoice') !== false) {
        $invoiceRoutes[] = $route->uri() . ' [' . implode(',', $route->methods()) . ']';
    }
}
echo "Invoice routes found: " . count($invoiceRoutes) . "\n";
foreach ($invoiceRoutes as $r) {
    echo "  - {$r}\n";
}
echo "\n";

// Test 5: Database Connection
echo "5️⃣ DATABASE CONNECTION:\n";
echo "--------------------------------------\n";
try {
    DB::connection()->getPdo();
    echo "✅ Database connected\n";
    echo "  - Driver: " . DB::connection()->getDriverName() . "\n";
    echo "  - Database: " . DB::connection()->getDatabaseName() . "\n\n";
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n\n";
}

// Test 6: Select2 Check in View
echo "6️⃣ SELECT2 LIBRARY CHECK:\n";
echo "--------------------------------------\n";
if (file_exists($viewPath)) {
    $viewContent = file_get_contents($viewPath);
    
    if (strpos($viewContent, 'select2') !== false) {
        echo "✅ select2 class found on PR Number field\n";
    } else {
        echo "⚠️  select2 not found\n";
    }
    
    // Check for select2 CSS
    if (strpos($viewContent, 'select2.min.css') !== false || strpos($viewContent, 'select2/css/select2') !== false) {
        echo "✅ Select2 CSS included\n";
    } else {
        echo "⚠️  Select2 CSS may be missing\n";
    }
    
    // Check for select2 JS initialization
    if (strpos($viewContent, "('.select2').select2") !== false || strpos($viewContent, '(".select2").select2') !== false) {
        echo "✅ Select2 initialized in JavaScript\n";
    } else {
        echo "⚠️  Select2 initialization may be missing\n";
    }
    echo "\n";
}

// Test 7: Simulate Dropdown HTML Output
echo "7️⃣ SIMULATED DROPDOWN OUTPUT:\n";
echo "--------------------------------------\n";
$pr_number_idd = App\Models\Project::select('id', 'pr_number', 'name')->get();
echo "<select name=\"pr_number\" id=\"pr_number\" class=\"form-control select2\">\n";
echo "    <option value=\"\" selected disabled>Select PR Number</option>\n";
if ($pr_number_idd->isEmpty()) {
    echo "    <!-- ❌ NO OPTIONS - NO PROJECTS IN DATABASE -->\n";
} else {
    foreach ($pr_number_idd as $pr_number_id) {
        echo "    <option value=\"{$pr_number_id->id}\" data-project-name=\"{$pr_number_id->name}\">\n";
        echo "        {$pr_number_id->pr_number}\n";
        echo "    </option>\n";
    }
}
echo "</select>\n\n";

// Test 8: Cache Issue Check
echo "8️⃣ CACHE CHECK (Hostinger Issue):\n";
echo "--------------------------------------\n";
echo "Cache Driver: " . config('cache.default') . "\n";

// Clear and rebuild cache
Cache::forget('projects_list');
echo "✅ Cleared projects_list cache\n";

$fresh = Cache::remember('projects_list', 3600, function () {
    return App\Models\Project::select('id', 'pr_number', 'name')->get();
});
echo "✅ Rebuilt cache: " . $fresh->count() . " projects\n\n";

// Test 9: Common Hostinger Issues
echo "9️⃣ COMMON HOSTINGER ISSUES:\n";
echo "--------------------------------------\n";
echo "Checking potential Hostinger problems:\n\n";

// Check permissions
$storagePath = storage_path('framework/cache');
if (is_writable($storagePath)) {
    echo "✅ Cache directory is writable\n";
} else {
    echo "❌ Cache directory NOT writable! (chmod 775 needed)\n";
}

// Check .env
if (file_exists(base_path('.env'))) {
    echo "✅ .env file exists\n";
    
    $env = file_get_contents(base_path('.env'));
    if (strpos($env, 'APP_DEBUG=true') !== false) {
        echo "⚠️  APP_DEBUG=true (good for testing, disable in production)\n";
    }
    
    if (strpos($env, 'DB_HOST=') !== false) {
        echo "✅ DB_HOST configured\n";
    }
} else {
    echo "❌ .env file missing!\n";
}

echo "\n";

// Test 10: JavaScript Console Check
echo "🔟 JAVASCRIPT/AJAX CHECK:\n";
echo "--------------------------------------\n";
if (file_exists($viewPath)) {
    $viewContent = file_get_contents($viewPath);
    
    // Check for jQuery
    if (strpos($viewContent, 'jquery') !== false || strpos($viewContent, '$') !== false) {
        echo "✅ jQuery appears to be used\n";
    } else {
        echo "⚠️  jQuery may be missing\n";
    }
    
    // Check for console.log (debugging)
    if (strpos($viewContent, 'console.log') !== false) {
        echo "ℹ️  console.log statements found (check browser console)\n";
    }
    
    // Check for select2 CDN or local
    if (strpos($viewContent, 'cdn') !== false && strpos($viewContent, 'select2') !== false) {
        echo "✅ Select2 loaded from CDN\n";
    } elseif (strpos($viewContent, 'assets/plugins/select2') !== false) {
        echo "✅ Select2 loaded locally from assets\n";
    }
}

echo "\n========================================\n";
echo "📊 SUMMARY:\n";
echo "========================================\n";

if ($projects->isEmpty()) {
    echo "❌ MAIN ISSUE: No projects in database!\n";
    echo "   Solution: Add projects first\n\n";
} else {
    echo "✅ Projects exist in database\n";
    echo "   If dropdown still empty on Hostinger:\n";
    echo "   1. Clear cache: php artisan cache:clear\n";
    echo "   2. Clear config: php artisan config:clear\n";
    echo "   3. Clear view: php artisan view:clear\n";
    echo "   4. Check file permissions: chmod 775 storage -R\n";
    echo "   5. Check browser console for JavaScript errors\n";
    echo "   6. Verify Select2 assets are uploaded\n\n";
}

echo "========================================\n";
