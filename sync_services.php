<?php
// Sync services from Smmwiz
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/SmmwizClient.php';

header('Content-Type: text/plain');

echo "=== SYNCING SERVICES FROM SMMWIZ ===\n\n";

$client = new SmmwizClient();
$smmwizServices = $client->services();

echo "Found " . count($smmwizServices) . " services in Smmwiz\n\n";

$inserted = 0;
$updated = 0;

// Get existing services to check duplicates
$existing = Database::fetchAll("SELECT smmwiz_id FROM services");

// Delete old sample services first
Database::query("DELETE FROM services WHERE smmwiz_id <= 100");
echo "Deleted old sample services\n";

foreach ($smmwizServices as $s) {
    // Detect platform from category/name
    $platform = 'other';
    $nameLower = strtolower($s['name']);
    if (stripos($nameLower, 'instagram') !== false || stripos($s['category'], 'Instagram') !== false) {
        $platform = 'instagram';
    } elseif (stripos($nameLower, 'facebook') !== false || stripos($s['category'], 'Facebook') !== false) {
        $platform = 'facebook';
    } elseif (stripos($nameLower, 'tiktok') !== false) {
        $platform = 'tiktok';
    } elseif (stripos($nameLower, 'youtube') !== false || stripos($s['category'], 'YouTube') !== false) {
        $platform = 'youtube';
    } elseif (stripos($nameLower, 'twitter') !== false || stripos($nameLower, 'x.com') !== false) {
        $platform = 'twitter';
    }

    // Determine category
    $category = $s['category'] ?? 'Other';

    // Calculate our price with 20% markup
    $ourRate = $s['rate'] * 1.20;

    try {
        Database::query(
            "INSERT INTO services (smmwiz_id, platform, name, type, category, min_quantity, max_quantity, rate, our_rate, markup_percentage, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')",
            [
                $s['service'],
                $platform,
                $s['name'],
                $s['type'] ?? 'Default',
                $category,
                $s['min'],
                $s['max'],
                $s['rate'],
                $ourRate,
                20.00
            ]
        );
        $inserted++;
    } catch (Exception $e) {
        // Ignore duplicates - just count
    }

    if ($inserted % 500 === 0) {
        echo "Inserted $inserted services...\n";
    }
}

echo "\n=== DONE ===\n";
echo "Total services in database: " . Database::count('services') . "\n";

// Show some examples
echo "\n=== SAMPLE PRICES (First 10) ===\n";
$services = Database::fetchAll("SELECT smmwiz_id, name, rate, our_rate FROM services ORDER BY id LIMIT 10");
foreach ($services as $s) {
    echo "ID: {$s['smmwiz_id']} | {$s['name']}\n";
    echo "  Cost: \${$s['rate']} | Your Price: \${$s['our_rate']} (+20%)\n\n";
}