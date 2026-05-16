<?php
// Sync top services from Smmwiz (quick version)
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/SmmwizClient.php';

header('Content-Type: text/plain');

echo "=== SYNCING TOP SERVICES FROM SMMWIZ ===\n\n";

// Clear existing services
Database::query("DELETE FROM services");
echo "Cleared old services\n";

$client = new SmmwizClient();
$smmwizServices = $client->services();

echo "Found " . count($smmwizServices) . " services in Smmwiz\n";

// Process in batches of 100
$batch = [];
$inserted = 0;

foreach ($smmwizServices as $s) {
    $platform = 'other';
    $nameLower = strtolower($s['name']);
    $catLower = strtolower($s['category'] ?? '');

    if (stripos($nameLower, 'instagram') !== false || stripos($catLower, 'instagram') !== false) {
        $platform = 'instagram';
    } elseif (stripos($nameLower, 'facebook') !== false || stripos($catLower, 'facebook') !== false) {
        $platform = 'facebook';
    } elseif (stripos($nameLower, 'tiktok') !== false) {
        $platform = 'tiktok';
    } elseif (stripos($nameLower, 'youtube') !== false || stripos($catLower, 'youtube') !== false) {
        $platform = 'youtube';
    } elseif (stripos($nameLower, 'twitter') !== false || stripos($nameLower, 'x.com') !== false) {
        $platform = 'twitter';
    }

    $ourRate = $s['rate'] * 1.20;

    // Insert directly (PostgreSQL UPSERT)
    try {
        Database::query(
            "INSERT INTO services (smmwiz_id, platform, name, type, category, min_quantity, max_quantity, rate, our_rate, markup_percentage, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')
             ON CONFLICT (smmwiz_id) DO NOTHING",
            [
                $s['service'], $platform, $s['name'], $s['type'] ?? 'Default',
                $s['category'] ?? 'Other', $s['min'], $s['max'],
                $s['rate'], $ourRate, 20.00
            ]
        );
        $inserted++;
    } catch (Exception $e) {
        // Skip errors
    }

    if ($inserted % 500 === 0) {
        echo "Synced $inserted services...\n";
    }
}

echo "\n=== DONE ===\n";
echo "Total services in database: " . Database::count('services') . "\n";

// Show sample prices
echo "\n=== SAMPLE PRICES ===\n";
$services = Database::fetchAll("SELECT smmwiz_id, platform, name, rate, our_rate FROM services WHERE status = 'active' ORDER BY id LIMIT 15");
foreach ($services as $s) {
    echo "[" . strtoupper($s['platform']) . "] {$s['name']}\n";
    echo "  Smmwiz: \${$s['rate']} → Your Price: \${$s['our_rate']}\n\n";
}