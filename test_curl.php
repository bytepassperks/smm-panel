<?php
// Test PHP cURL
error_reporting(E_ALL);

echo "Testing PHP cURL...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://smmwiz.com/api/v2");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "key=590d78f5f73a1e4a5816fa7c997e0bf6&action=balance");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$result = curl_exec($ch);
$error = curl_error($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

echo "HTTP Code: $http_code\n";
echo "Result: $result\n";
echo "Error: $error\n";