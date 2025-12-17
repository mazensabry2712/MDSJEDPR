<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "🧪 INVOICE PR NUMBER TEST\n";
echo "========================================\n\n";

// Create test invoice
echo "📝 Creating Test Invoice...\n";
echo "--------------------------------------\n";

try {
    // Get first project
    $project = App\Models\Project::first();

    if (!$project) {
        echo "❌ No project found! Create a project first.\n";
        exit(1);
    }

    echo "Using Project:\n";
    echo "  - ID: {$project->id}\n";
    echo "  - PR Number: {$project->pr_number}\n";
    echo "  - Name: {$project->name}\n\n";

    // Create invoice
    $invoice = App\Models\invoices::create([
        'invoice_number' => 'INV-TEST-001',
        'pr_number' => $project->id,
        'value' => 1000.00,
        'status' => 'pending',
        'pr_invoices_total_value' => 1000.00,
    ]);

    echo "✅ Invoice Created!\n";
    echo "  - ID: {$invoice->id}\n";
    echo "  - Invoice Number: {$invoice->invoice_number}\n";
    echo "  - PR Number (Stored): {$invoice->pr_number}\n";
    echo "  - Value: {$invoice->value}\n\n";

} catch (Exception $e) {
    echo "❌ Error creating invoice: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test relationship
echo "🔗 Testing Relationship...\n";
echo "--------------------------------------\n";

try {
    // Refresh invoice from database
    $invoice = App\Models\invoices::find($invoice->id);

    echo "Invoice Data:\n";
    echo "  - pr_number (raw): {$invoice->pr_number}\n";
    echo "  - pr_number type: " . gettype($invoice->pr_number) . "\n\n";

    // Try to get project
    $relatedProject = $invoice->project;

    if ($relatedProject) {
        echo "✅ Relationship WORKING!\n";
        echo "  - Project ID: {$relatedProject->id}\n";
        echo "  - Project PR: {$relatedProject->pr_number}\n";
        echo "  - Project Name: {$relatedProject->name}\n\n";
    } else {
        echo "❌ Relationship NOT WORKING!\n";
        echo "  - project is NULL\n\n";

        // Debug
        echo "Debug Info:\n";
        echo "  - Looking for project with ID: {$invoice->pr_number}\n";
        $debugProject = App\Models\Project::find($invoice->pr_number);
        if ($debugProject) {
            echo "  - Project EXISTS in database\n";
        } else {
            echo "  - Project NOT FOUND in database\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Relationship Error: " . $e->getMessage() . "\n\n";
}

// Check Invoice Model
echo "🔍 Checking Invoice Model...\n";
echo "--------------------------------------\n";

try {
    $modelFile = file_get_contents(app_path('Models/invoices.php'));

    // Check for project relationship
    if (strpos($modelFile, 'function project()') !== false) {
        echo "✅ project() method exists\n";

        // Extract the method
        preg_match('/function project\(\)[\s\S]*?}/', $modelFile, $matches);
        if (!empty($matches)) {
            echo "\nMethod Code:\n";
            echo "```php\n";
            echo trim($matches[0]) . "\n";
            echo "```\n\n";

            // Check if it's using the correct relationship
            if (strpos($matches[0], 'belongsTo') !== false) {
                echo "✅ Using belongsTo relationship\n";

                if (strpos($matches[0], "'pr_number'") !== false) {
                    echo "✅ Foreign key 'pr_number' specified\n";
                } else {
                    echo "⚠️  Foreign key may not be specified correctly\n";
                }

                if (strpos($matches[0], "'id'") !== false) {
                    echo "✅ Owner key 'id' specified\n";
                } else {
                    echo "⚠️  Owner key may not be specified correctly\n";
                }
            } else {
                echo "❌ Not using belongsTo!\n";
            }
        }
    } else {
        echo "❌ project() method NOT FOUND!\n";
    }

} catch (Exception $e) {
    echo "❌ Error checking model: " . $e->getMessage() . "\n\n";
}

// Test with direct query
echo "\n🔎 Direct Query Test...\n";
echo "--------------------------------------\n";

try {
    $result = DB::select("
        SELECT
            i.id,
            i.invoice_number,
            i.pr_number,
            i.value,
            p.id as project_id,
            p.pr_number as project_pr,
            p.name as project_name
        FROM invoices i
        LEFT JOIN projects p ON i.pr_number = p.id
        WHERE i.id = ?
    ", [$invoice->id]);

    if (!empty($result)) {
        $row = $result[0];
        echo "Direct SQL Query Result:\n";
        echo "  - Invoice ID: {$row->id}\n";
        echo "  - Invoice Number: {$row->invoice_number}\n";
        echo "  - Invoice pr_number: {$row->pr_number}\n";
        echo "  - Project ID: " . ($row->project_id ?? 'NULL') . "\n";
        echo "  - Project PR: " . ($row->project_pr ?? 'NULL') . "\n";
        echo "  - Project Name: " . ($row->project_name ?? 'NULL') . "\n\n";

        if ($row->project_id) {
            echo "✅ JOIN is working in SQL!\n";
        } else {
            echo "❌ JOIN is NOT working in SQL!\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Query Error: " . $e->getMessage() . "\n\n";
}

// Cleanup
echo "\n🧹 Cleanup...\n";
echo "--------------------------------------\n";
echo "Delete test invoice? (keeping it for now)\n";
echo "To delete manually: DELETE FROM invoices WHERE invoice_number = 'INV-TEST-001'\n\n";

echo "========================================\n";
echo "Test completed at: " . date('Y-m-d H:i:s') . "\n";
echo "========================================\n";
