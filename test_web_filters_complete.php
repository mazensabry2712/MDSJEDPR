<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== Testing Report Filters via HTTP Requests ===\n\n";

// Test 1: No filters - All projects
echo "Test 1: GET /reports (No filters)\n";
echo str_repeat("-", 50) . "\n";

$request = Illuminate\Http\Request::create('/reports', 'GET');
$response = $kernel->handle($request);
$content = $response->getContent();

// Count table rows in tbody
preg_match_all('/<tbody>(.*?)<\/tbody>/s', $content, $tbody_matches);
if (isset($tbody_matches[1][0])) {
    $rows_count = substr_count($tbody_matches[1][0], '<tr>');
    echo "✓ Total projects found: $rows_count\n";

    // Extract PR numbers
    preg_match_all('/badge badge-info">([^<]+)</', $tbody_matches[1][0], $pr_matches);
    if (!empty($pr_matches[1])) {
        echo "✓ PR Numbers: " . implode(", ", $pr_matches[1]) . "\n";
    }
} else {
    echo "✗ No results table found\n";
}

echo "\n";

// Test 2: Filter by PR Number = 1
echo "Test 2: GET /reports?filter[pr_number]=1\n";
echo str_repeat("-", 50) . "\n";

$request2 = Illuminate\Http\Request::create('/reports?filter[pr_number]=1', 'GET');
$response2 = $kernel->handle($request2);
$content2 = $response2->getContent();

preg_match_all('/<tbody>(.*?)<\/tbody>/s', $content2, $tbody_matches2);
if (isset($tbody_matches2[1][0])) {
    $rows_count2 = substr_count($tbody_matches2[1][0], '<tr>');

    // Extract PR numbers
    preg_match_all('/badge badge-info">([^<]+)</', $tbody_matches2[1][0], $pr_matches2);

    if ($rows_count2 == 1 && isset($pr_matches2[1][0]) && $pr_matches2[1][0] == '1') {
        echo "✓ PASS: Found exactly 1 project with PR = 1\n";
        echo "✓ PR Number: " . $pr_matches2[1][0] . "\n";

        // Extract project name
        preg_match('/<strong>([^<]+)<\/strong>/', $tbody_matches2[1][0], $name_match);
        if (isset($name_match[1])) {
            echo "✓ Project Name: " . $name_match[1] . "\n";
        }
    } else {
        echo "✗ FAIL: Expected 1 result with PR=1, got $rows_count2 results\n";
        if (!empty($pr_matches2[1])) {
            echo "✗ PR Numbers found: " . implode(", ", $pr_matches2[1]) . "\n";
        }
    }
} else {
    // Check for "No results" message
    if (strpos($content2, 'No results found') !== false) {
        echo "✗ FAIL: No results found (expected 1)\n";
    }
}

echo "\n";

// Test 3: Filter by PR Number = 11
echo "Test 3: GET /reports?filter[pr_number]=11\n";
echo str_repeat("-", 50) . "\n";

$request3 = Illuminate\Http\Request::create('/reports?filter[pr_number]=11', 'GET');
$response3 = $kernel->handle($request3);
$content3 = $response3->getContent();

preg_match_all('/<tbody>(.*?)<\/tbody>/s', $content3, $tbody_matches3);
if (isset($tbody_matches3[1][0])) {
    $rows_count3 = substr_count($tbody_matches3[1][0], '<tr>');

    preg_match_all('/badge badge-info">([^<]+)</', $tbody_matches3[1][0], $pr_matches3);

    if ($rows_count3 == 1 && isset($pr_matches3[1][0]) && $pr_matches3[1][0] == '11') {
        echo "✓ PASS: Found exactly 1 project with PR = 11\n";
        echo "✓ PR Number: " . $pr_matches3[1][0] . "\n";

        preg_match('/<strong>([^<]+)<\/strong>/', $tbody_matches3[1][0], $name_match3);
        if (isset($name_match3[1])) {
            echo "✓ Project Name: " . $name_match3[1] . "\n";
        }
    } else {
        echo "✗ FAIL: Expected 1 result with PR=11, got $rows_count3 results\n";
        if (!empty($pr_matches3[1])) {
            echo "✗ PR Numbers found: " . implode(", ", $pr_matches3[1]) . "\n";
        }
    }
}

echo "\n";

// Test 4: Filter by Project Name
echo "Test 4: GET /reports?filter[name]=mazen sabry\n";
echo str_repeat("-", 50) . "\n";

$request4 = Illuminate\Http\Request::create('/reports?filter[name]=' . urlencode('mazen sabry'), 'GET');
$response4 = $kernel->handle($request4);
$content4 = $response4->getContent();

preg_match_all('/<tbody>(.*?)<\/tbody>/s', $content4, $tbody_matches4);
if (isset($tbody_matches4[1][0])) {
    $rows_count4 = substr_count($tbody_matches4[1][0], '<tr>');

    preg_match('/<strong>([^<]+)<\/strong>/', $tbody_matches4[1][0], $name_match4);

    if ($rows_count4 == 1 && isset($name_match4[1]) && trim($name_match4[1]) == 'mazen sabry') {
        echo "✓ PASS: Found exactly 1 project with name 'mazen sabry'\n";
        echo "✓ Project Name: " . $name_match4[1] . "\n";
    } else {
        echo "✗ FAIL: Expected 1 result with name='mazen sabry', got $rows_count4 results\n";
    }
}

echo "\n";

// Test 5: Filter by Technologies (partial match)
echo "Test 5: GET /reports?filter[technologies]=يشسي\n";
echo str_repeat("-", 50) . "\n";

$request5 = Illuminate\Http\Request::create('/reports?filter[technologies]=' . urlencode('يشسي'), 'GET');
$response5 = $kernel->handle($request5);
$content5 = $response5->getContent();

preg_match_all('/<tbody>(.*?)<\/tbody>/s', $content5, $tbody_matches5);
if (isset($tbody_matches5[1][0])) {
    $rows_count5 = substr_count($tbody_matches5[1][0], '<tr>');

    // Extract technologies
    preg_match('/badge badge-secondary">([^<]+)</', $tbody_matches5[1][0], $tech_match);

    if ($rows_count5 >= 1) {
        echo "✓ PASS: Found $rows_count5 project(s) with technologies containing 'يشسي'\n";
        if (isset($tech_match[1])) {
            echo "✓ Technologies: " . $tech_match[1] . "\n";
        }
    } else {
        echo "✗ FAIL: Expected at least 1 result\n";
    }
}

echo "\n";

// Summary
echo str_repeat("=", 50) . "\n";
echo "TEST SUMMARY\n";
echo str_repeat("=", 50) . "\n";
echo "All tests completed. Check results above.\n";
echo "\nNote: If all tests show ✓ PASS, filters are working correctly!\n";

$kernel->terminate($request, $response);
