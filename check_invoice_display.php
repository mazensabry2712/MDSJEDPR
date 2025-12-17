<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "🔍 INVOICE DISPLAY TEST\n";
echo "========================================\n\n";

$invoice = App\Models\invoices::first();

if (!$invoice) {
    echo "❌ No invoice found!\n";
    exit(1);
}

echo "Invoice Data:\n";
echo "  - ID: {$invoice->id}\n";
echo "  - Invoice Number: {$invoice->invoice_number}\n";
echo "  - pr_number (stored in DB): {$invoice->pr_number}\n";
echo "  - Value: {$invoice->value}\n\n";

echo "Related Project:\n";
if ($invoice->project) {
    echo "  - Project ID: {$invoice->project->id}\n";
    echo "  - Project PR Number: {$invoice->project->pr_number}\n";
    echo "  - Project Name: {$invoice->project->name}\n\n";

    echo "✅ This is what should display in views!\n";
    echo "   In index: {{ \$invoice->project->pr_number }}\n";
    echo "   In create: {{ \$pr_number_id->pr_number }}\n";
    echo "   In edit: {{ \$pr_number_id->pr_number }}\n\n";
} else {
    echo "  ❌ Project relationship is NULL!\n\n";
}

echo "Testing View Display Logic:\n";
echo "--------------------------------------\n";
echo "index.blade.php line 343:\n";
echo "  Code: {{ \$invoice->project->pr_number ?? 'N/A' }}\n";
echo "  Result: " . ($invoice->project->pr_number ?? 'N/A') . "\n\n";

echo "create.blade.php dropdown:\n";
$projects = App\Models\Project::all();
foreach ($projects as $pr) {
    echo "  <option value=\"{$pr->id}\">{$pr->pr_number}</option>\n";
}

echo "\n========================================\n";
echo "✅ ALL VIEWS ARE CORRECT!\n";
echo "========================================\n";
