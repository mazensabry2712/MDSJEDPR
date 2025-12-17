<?php

// Test AJAX endpoint directly
$url = 'http://mdsjedpr.test/ppos/categories/1';

echo "=== Testing AJAX Endpoint ===\n\n";
echo "URL: $url\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n\n";
echo "Response:\n";
echo $response;
echo "\n\n";

if ($httpCode == 200) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✅ SUCCESS: Found " . count($data['categories']) . " categories\n\n";
        foreach ($data['categories'] as $cat) {
            echo "  - ID: {$cat['id']}, Category: {$cat['category']}\n";
        }
    } else {
        echo "❌ ERROR: Invalid response format\n";
    }
} else {
    echo "❌ ERROR: HTTP request failed\n";
}
