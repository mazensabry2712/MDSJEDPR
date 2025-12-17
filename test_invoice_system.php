<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "🧪 INVOICE SYSTEM COMPREHENSIVE TEST\n";
echo "========================================\n\n";

// Test 1: Check Database Connection & Data
echo "📊 Test 1: Database & Data Check\n";
echo "--------------------------------------\n";
try {
    $projectCount = App\Models\Project::count();
    $invoiceCount = App\Models\invoices::count();

    echo "✅ Projects: $projectCount\n";
    echo "✅ Invoices: $invoiceCount\n\n";

    if ($invoiceCount == 0) {
        echo "⚠️  No invoices found! Creating test data...\n\n";
    }
} catch (Exception $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 2: Check Invoice Model Structure
echo "🔍 Test 2: Invoice Model Structure\n";
echo "--------------------------------------\n";
try {
    $invoice = new App\Models\invoices();
    $fillable = $invoice->getFillable();

    echo "Fillable Fields:\n";
    foreach ($fillable as $field) {
        echo "  - $field\n";
    }
    echo "\n";

    // Check relationships
    $relations = get_class_methods(App\Models\invoices::class);
    echo "Available Methods:\n";
    foreach ($relations as $method) {
        if (!str_starts_with($method, '_') && !str_starts_with($method, 'get') && !str_starts_with($method, 'set')) {
            if (in_array($method, ['project', 'Project'])) {
                echo "  ✅ $method (Relationship)\n";
            }
        }
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ Model Error: " . $e->getMessage() . "\n\n";
}

// Test 3: Check Actual Invoice Data
echo "📋 Test 3: Invoice Data Analysis\n";
echo "--------------------------------------\n";
try {
    $invoices = App\Models\invoices::all();

    if ($invoices->isEmpty()) {
        echo "⚠️  No invoice records found\n\n";
    } else {
        echo "Found {$invoices->count()} invoice(s):\n\n";

        foreach ($invoices->take(5) as $invoice) {
            echo "Invoice #{$invoice->id}:\n";
            echo "  - Invoice Number: {$invoice->invoice_number}\n";
            echo "  - PR Number (Raw): {$invoice->pr_number}\n";
            echo "  - Value: {$invoice->value}\n";
            echo "  - Total Value: {$invoice->pr_invoices_total_value}\n";

            // Try to get project
            try {
                $project = $invoice->project;
                if ($project) {
                    echo "  - Project Name: {$project->name}\n";
                    echo "  - Project PR: {$project->pr_number}\n";
                    echo "  ✅ Relationship Working!\n";
                } else {
                    echo "  ❌ Project NOT FOUND for pr_number: {$invoice->pr_number}\n";
                }
            } catch (Exception $e) {
                echo "  ❌ Relationship Error: " . $e->getMessage() . "\n";
            }
            echo "\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Data Error: " . $e->getMessage() . "\n\n";
}

// Test 4: Check Database Table Structure
echo "🗄️  Test 4: Database Table Structure\n";
echo "--------------------------------------\n";
try {
    $columns = DB::select("SHOW COLUMNS FROM invoices");

    echo "Invoices Table Columns:\n";
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type})";
        if ($column->Key == 'MUL') {
            echo " [FOREIGN KEY]";
        }
        if ($column->Key == 'PRI') {
            echo " [PRIMARY KEY]";
        }
        echo "\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ Table Structure Error: " . $e->getMessage() . "\n\n";
}

// Test 5: Test Project-Invoice Relationship
echo "🔗 Test 5: Relationship Test\n";
echo "--------------------------------------\n";
try {
    $projects = App\Models\Project::all();

    if ($projects->isEmpty()) {
        echo "⚠️  No projects found\n\n";
    } else {
        foreach ($projects as $project) {
            echo "Project: {$project->name} (ID: {$project->id}, PR: {$project->pr_number})\n";

            // Check invoices for this project
            $projectInvoices = App\Models\invoices::where('pr_number', $project->id)->get();

            if ($projectInvoices->isEmpty()) {
                echo "  ⚠️  No invoices linked to this project\n";
            } else {
                echo "  ✅ Found {$projectInvoices->count()} invoice(s)\n";
                foreach ($projectInvoices as $inv) {
                    echo "     - Invoice: {$inv->invoice_number}, Value: {$inv->value}\n";
                }
            }
            echo "\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Relationship Test Error: " . $e->getMessage() . "\n\n";
}

// Test 6: Check Invoice Controller
echo "🎛️  Test 6: Invoice Controller Check\n";
echo "--------------------------------------\n";
try {
    $controller = new App\Http\Controllers\InvoicesController();

    if (method_exists($controller, 'index')) {
        echo "✅ index() method exists\n";
    }

    if (method_exists($controller, 'store')) {
        echo "✅ store() method exists\n";
    }

    if (method_exists($controller, 'update')) {
        echo "✅ update() method exists\n";
    }

    echo "\n";
} catch (Exception $e) {
    echo "❌ Controller Error: " . $e->getMessage() . "\n\n";
}

// Test 7: Check Invoice Views
echo "👁️  Test 7: Invoice View Files\n";
echo "--------------------------------------\n";
$viewPaths = [
    'index' => resource_path('views/dashboard/invoice/index.blade.php'),
    'create' => resource_path('views/dashboard/invoice/create.blade.php'),
    'edit' => resource_path('views/dashboard/invoice/edit.blade.php'),
];

foreach ($viewPaths as $name => $path) {
    if (file_exists($path)) {
        echo "✅ {$name}.blade.php exists\n";

        $content = file_get_contents($path);

        // Check for pr_number field
        if (strpos($content, 'pr_number') !== false) {
            echo "   ✅ Contains 'pr_number' field\n";
        } else {
            echo "   ❌ Missing 'pr_number' field\n";
        }

        // Check for project relationship display
        if (strpos($content, '->project') !== false || strpos($content, 'project->') !== false) {
            echo "   ✅ Uses project relationship\n";
        } else {
            echo "   ⚠️  May not be using project relationship\n";
        }
    } else {
        echo "❌ {$name}.blade.php NOT FOUND\n";
    }
}
echo "\n";

// Test 8: Check Routes
echo "🛣️  Test 8: Invoice Routes\n";
echo "--------------------------------------\n";
try {
    $routes = Route::getRoutes();
    $invoiceRoutes = [];

    foreach ($routes as $route) {
        if (strpos($route->uri(), 'invoice') !== false || strpos($route->getName() ?? '', 'invoice') !== false) {
            $invoiceRoutes[] = $route->uri() . ' (' . implode('|', $route->methods()) . ')';
        }
    }

    if (empty($invoiceRoutes)) {
        echo "❌ No invoice routes found!\n";
    } else {
        echo "Found " . count($invoiceRoutes) . " invoice route(s):\n";
        foreach (array_slice($invoiceRoutes, 0, 10) as $route) {
            echo "  - $route\n";
        }
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ Routes Error: " . $e->getMessage() . "\n\n";
}

// Test 9: Data Integrity Check
echo "🔍 Test 9: Data Integrity Check\n";
echo "--------------------------------------\n";
try {
    // Check for orphaned invoices
    $orphanedInvoices = DB::select("
        SELECT i.id, i.invoice_number, i.pr_number
        FROM invoices i
        LEFT JOIN projects p ON i.pr_number = p.id
        WHERE p.id IS NULL
    ");

    if (empty($orphanedInvoices)) {
        echo "✅ No orphaned invoices (all have valid project links)\n";
    } else {
        echo "❌ Found " . count($orphanedInvoices) . " orphaned invoice(s):\n";
        foreach ($orphanedInvoices as $orphan) {
            echo "   - Invoice #{$orphan->id} ({$orphan->invoice_number}) → PR: {$orphan->pr_number} (NOT FOUND)\n";
        }
    }
    echo "\n";
} catch (Exception $e) {
    echo "⚠️  Could not check data integrity: " . $e->getMessage() . "\n\n";
}

// Summary
echo "========================================\n";
echo "📊 TEST SUMMARY\n";
echo "========================================\n";

$issues = [];

// Check if we have data
if ($invoiceCount == 0) {
    $issues[] = "No invoice data - add invoices to test properly";
}

if ($projectCount == 0) {
    $issues[] = "No projects - add projects first";
}

// Check for orphaned records
if (!empty($orphanedInvoices)) {
    $issues[] = count($orphanedInvoices) . " orphaned invoice(s) with invalid pr_number";
}

// Check model
try {
    $invoice = new App\Models\invoices();
    if (!in_array('pr_number', $invoice->getFillable())) {
        $issues[] = "'pr_number' not in fillable array";
    }
} catch (Exception $e) {
    $issues[] = "Invoice model error: " . $e->getMessage();
}

if (empty($issues)) {
    echo "✅ All basic checks passed!\n";
    echo "🎉 Invoice system structure looks good!\n";
} else {
    echo "⚠️  Found " . count($issues) . " issue(s):\n\n";
    foreach ($issues as $i => $issue) {
        echo ($i + 1) . ". " . $issue . "\n";
    }
}

echo "\n========================================\n";
echo "Test completed at: " . date('Y-m-d H:i:s') . "\n";
echo "========================================\n";
