<?php
// Check prices
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/SmmwizClient.php';

header('Content-Type: text/plain');

echo "=== DATABASE PRICES ===\n";
$services = Database::fetchAll("SELECT id, smmwiz_id, name, rate, our_rate, markup_percentage FROM services ORDER BY id LIMIT 10");
foreach ($services as $s) {
    echo "ID: {$s['smmwiz_id']} | {$s['name']}\n";
    echo "  Smmwiz Cost: \${$s['rate']} | Your Price: \${$s['our_rate']} | Markup: {$s['markup_percentage']}%\n";
}

echo "\n=== SMMWIZ API PRICES ===\n";
$client = new SmmwizClient();
$smmwizServices = $client->services();

// Show first 10 from API
$count = 0;
foreach ($smmwizServices as $s) {
    if ($count >= 10) break;
    echo "ID: {$s['service']} | {$s['name']}\n";
    echo "  Smmwiz Rate: \${$s['rate']}\n";
    $count++;
}

echo "\n=== CHECKING SPECIFIC SERVICE ===\n";
// Check Instagram Followers (service ID 1 in DB)
$dbService = Database::fetch("SELECT * FROM services WHERE id = 1");
echo "DB Service: " . print_r($dbService, true) . "\n";

// Find same service in Smmwiz
foreach ($smmwizServices as $s) {
    if (stripos($s['name'], 'Followers') !== false && stripos($s['name'], 'Instagram') !== false) {
        echo "\nMatching Smmwiz Service:\n";
        print_r($s);
        break;
    }
}