<?php
// Test Smmwiz API
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/SmmwizClient.php';

echo "Testing Smmwiz API...\n";

$client = new SmmwizClient();

echo "1. Getting balance...\n";
try {
    $balance = $client->balance();
    print_r($balance);
} catch (Exception $e) {
    echo "Balance Error: " . $e->getMessage() . "\n";
}

echo "\n2. Getting services...\n";
try {
    $services = $client->services();
    echo "Services count: " . count($services) . "\n";
    if (count($services) > 0) {
        print_r($services[0]);
    }
} catch (Exception $e) {
    echo "Services Error: " . $e->getMessage() . "\n";
}