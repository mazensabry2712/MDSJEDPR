#!/usr/bin/env php
<?php

/**
 * Export Buttons Migration Script
 *
 * This script automatically updates all Blade files to use the new unified export buttons system.
 *
 * Usage:
 *   php update-export-buttons.php [--dry-run] [--module=ModuleName]
 *
 * Options:
 *   --dry-run    Show changes without applying them
 *   --module     Update only specific module (e.g., --module=AMs)
 *
 * Examples:
 *   php update-export-buttons.php --dry-run
 *   php update-export-buttons.php --module=vendors
 *   php update-export-buttons.php
 */

class ExportButtonsMigration
{
    private $dryRun = false;
    private $specificModule = null;
    private $viewsPath;
    private $updated = [];
    private $errors = [];

    // Button mapping: old class => new class
    private $buttonClassMap = [
        'btn-outline-danger' => 'btn-pdf',
        'btn-outline-success' => 'btn-excel',
        'btn-outline-info' => 'btn-csv',
        'btn-outline-secondary' => 'btn-print',
    ];

    // Function mapping: old => new
    private $functionMap = [
        'exportToPDF()' => 'exportTableToPDF',
        'exportToExcel()' => 'exportTableToExcel',
        'exportToCSV()' => 'exportTableToCSV',
        'printTable()' => 'printTableData',
    ];

    public function __construct($args)
    {
        $this->viewsPath = __DIR__ . '/../resources/views/dashboard';

        // Parse arguments
        foreach ($args as $arg) {
            if ($arg === '--dry-run') {
                $this->dryRun = true;
            } elseif (strpos($arg, '--module=') === 0) {
                $this->specificModule = substr($arg, 9);
            }
        }
    }

    public function run()
    {
        $this->printHeader();

        if (!is_dir($this->viewsPath)) {
            $this->error("Views directory not found: {$this->viewsPath}");
            return false;
        }

        $modules = $this->getModules();
        $this->info("Found " . count($modules) . " modules to process\n");

        foreach ($modules as $module) {
            $this->processModule($module);
        }

        $this->printSummary();
        return true;
    }

    private function getModules()
    {
        $modules = [];
        $directories = glob($this->viewsPath . '/*', GLOB_ONLYDIR);

        foreach ($directories as $dir) {
            $moduleName = basename($dir);

            // Skip if specific module requested and this isn't it
            if ($this->specificModule && $moduleName !== $this->specificModule) {
                continue;
            }

            // Check if index.blade.php exists
            $indexFile = $dir . '/index.blade.php';
            if (file_exists($indexFile)) {
                $modules[] = [
                    'name' => $moduleName,
                    'path' => $dir,
                    'indexFile' => $indexFile
                ];
            }
        }

        return $modules;
    }

    private function processModule($module)
    {
        $this->info("\n📁 Processing: {$module['name']}");

        try {
            $content = file_get_contents($module['indexFile']);
            $originalContent = $content;
            $changes = 0;

            // Step 1: Add CSS if not present
            if (strpos($content, 'export-buttons.css') === false) {
                $content = $this->addCSS($content);
                $changes++;
                $this->success("  ✓ Added CSS include");
            }

            // Step 2: Add JS if not present
            if (strpos($content, 'export-functions.js') === false) {
                $content = $this->addJS($content);
                $changes++;
                $this->success("  ✓ Added JS include");
            }

            // Step 3: Update button classes
            $buttonChanges = $this->updateButtonClasses($content);
            if ($buttonChanges['changed']) {
                $content = $buttonChanges['content'];
                $changes++;
                $this->success("  ✓ Updated button classes");
            }

            // Step 4: Update onclick functions
            $functionChanges = $this->updateOnclickFunctions($content, $module['name']);
            if ($functionChanges['changed']) {
                $content = $functionChanges['content'];
                $changes++;
                $this->success("  ✓ Updated onclick functions");
            }

            // Step 5: Add ARIA labels and tooltips
            $ariaChanges = $this->addAriaLabels($content);
            if ($ariaChanges['changed']) {
                $content = $ariaChanges['content'];
                $changes++;
                $this->success("  ✓ Added ARIA labels");
            }

            // Save changes
            if ($changes > 0 && $content !== $originalContent) {
                if (!$this->dryRun) {
                    file_put_contents($module['indexFile'], $content);
                    $this->updated[] = $module['name'];
                    $this->success("  ✅ Updated successfully ({$changes} changes)");
                } else {
                    $this->info("  🔍 Would update ({$changes} changes) [DRY RUN]");
                }
            } else {
                $this->info("  ⏭️  No changes needed");
            }

        } catch (Exception $e) {
            $this->error("  ❌ Error: " . $e->getMessage());
            $this->errors[] = $module['name'] . ': ' . $e->getMessage();
        }
    }

    private function addCSS($content)
    {
        // Find @section('css') and add our CSS
        $pattern = '/(@section\([\'"]css[\'"]\).*?)((?=@stop|@endsection))/s';

        $replacement = '$1' . "\n    <!-- Unified Export Buttons CSS -->\n" .
                      '    <link href="{{ URL::asset(\'assets/css/export-buttons.css\') }}" rel="stylesheet">' . "\n\n" . '$2';

        return preg_replace($pattern, $replacement, $content, 1);
    }

    private function addJS($content)
    {
        // Find @section('js') and add our JS
        $pattern = '/(@section\([\'"]js[\'"]\).*?<script src=.*?table-data\.js.*?<\/script>)/s';

        $replacement = '$1' . "\n\n    <!-- Unified Export Functions -->\n" .
                      '    <script src="{{ URL::asset(\'assets/js/export-functions.js\') }}"></script>';

        return preg_replace($pattern, $replacement, $content, 1);
    }

    private function updateButtonClasses($content)
    {
        $changed = false;

        foreach ($this->buttonClassMap as $old => $new) {
            if (strpos($content, $old) !== false) {
                $content = str_replace($old, $new, $content);
                $changed = true;
            }
        }

        return ['content' => $content, 'changed' => $changed];
    }

    private function updateOnclickFunctions($content, $moduleName)
    {
        $changed = false;

        // Detect table ID (usually 'example1' or module-specific)
        preg_match('/id=["\']([^"\']*Table|example\d+)["\']/', $content, $matches);
        $tableId = $matches[1] ?? 'example1';

        // Generate title from module name
        $title = ucfirst($moduleName) . ' Report';

        // Update each function call
        $patterns = [
            '/onclick=["\']exportToPDF\(\)["\']/' =>
                'onclick="exportTableToPDF(\'' . $tableId . '\', \'' . $title . '\', [0, 6])"',
            '/onclick=["\']exportToExcel\(\)["\']/' =>
                'onclick="exportTableToExcel(\'' . $tableId . '\', \'' . $title . '\', [0, 6])"',
            '/onclick=["\']exportToCSV\(\)["\']/' =>
                'onclick="exportTableToCSV(\'' . $tableId . '\', \'' . $title . '\', [0, 6])"',
            '/onclick=["\']printTable\(\)["\']/' =>
                'onclick="printTableData(\'' . $tableId . '\', \'' . $title . '\')"',
        ];

        foreach ($patterns as $pattern => $replacement) {
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $replacement, $content);
                $changed = true;
            }
        }

        return ['content' => $content, 'changed' => $changed];
    }

    private function addAriaLabels($content)
    {
        $changed = false;

        // Add aria-label to buttons if not present
        $patterns = [
            '/<button([^>]*class=["\'][^"\']*btn-pdf[^"\']*["\'][^>]*)>/' =>
                '<button$1 aria-label="Export to PDF">',
            '/<button([^>]*class=["\'][^"\']*btn-excel[^"\']*["\'][^>]*)>/' =>
                '<button$1 aria-label="Export to Excel">',
            '/<button([^>]*class=["\'][^"\']*btn-csv[^"\']*["\'][^>]*)>/' =>
                '<button$1 aria-label="Export to CSV">',
            '/<button([^>]*class=["\'][^"\']*btn-print[^"\']*["\'][^>]*)>/' =>
                '<button$1 aria-label="Print">',
        ];

        foreach ($patterns as $pattern => $replacement) {
            if (preg_match($pattern, $content) && strpos($content, 'aria-label') === false) {
                $content = preg_replace($pattern, $replacement, $content);
                $changed = true;
            }
        }

        return ['content' => $content, 'changed' => $changed];
    }

    private function printHeader()
    {
        echo "\n";
        echo "╔══════════════════════════════════════════════════════════╗\n";
        echo "║   Export Buttons Migration Script                       ║\n";
        echo "║   MDSJEDPR - Corporate Sites Management System          ║\n";
        echo "╚══════════════════════════════════════════════════════════╝\n";
        echo "\n";

        if ($this->dryRun) {
            echo "🔍 DRY RUN MODE - No files will be modified\n";
        }

        if ($this->specificModule) {
            echo "📌 Processing only module: {$this->specificModule}\n";
        }
    }

    private function printSummary()
    {
        echo "\n";
        echo "╔══════════════════════════════════════════════════════════╗\n";
        echo "║   Migration Summary                                      ║\n";
        echo "╚══════════════════════════════════════════════════════════╝\n";
        echo "\n";

        echo "✅ Successfully updated: " . count($this->updated) . " modules\n";

        if (!empty($this->updated)) {
            foreach ($this->updated as $module) {
                echo "   - {$module}\n";
            }
        }

        if (!empty($this->errors)) {
            echo "\n❌ Errors: " . count($this->errors) . "\n";
            foreach ($this->errors as $error) {
                echo "   - {$error}\n";
            }
        }

        if ($this->dryRun) {
            echo "\n💡 This was a dry run. Run without --dry-run to apply changes.\n";
        } else {
            echo "\n🎉 Migration completed successfully!\n";
            echo "\n📝 Next steps:\n";
            echo "   1. Clear cache: php artisan view:clear\n";
            echo "   2. Test export buttons in each module\n";
            echo "   3. Review generated files for any issues\n";
        }

        echo "\n";
    }

    private function info($message)
    {
        echo $message . "\n";
    }

    private function success($message)
    {
        echo "\033[32m" . $message . "\033[0m\n";
    }

    private function error($message)
    {
        echo "\033[31m" . $message . "\033[0m\n";
    }
}

// Run the migration
$migration = new ExportButtonsMigration(array_slice($argv, 1));
$success = $migration->run();
exit($success ? 0 : 1);
