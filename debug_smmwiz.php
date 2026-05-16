<?php
// Debug SmmwizClient
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/SmmwizClient.php';

$client = new SmmwizClient();

// Test balance
echo "=== Testing Balance ===\n";
try {
    $result = $client->balance();
    print_r($result);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Testing Services ===\n";
try {
    $result = $client->services();
    echo "Count: " . count($result) . "\n";
    if (count($result) > 0) {
        print_r($result[0]);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}