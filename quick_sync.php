<?php
// Quick sync - top services only
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/SmmwizClient.php';

header('Content-Type: text/plain');

// Clear
Database::query("DELETE FROM services");

$client = new SmmwizClient();
$services = $client->services();

$targetPlatforms = ['instagram', 'facebook', 'tiktok', 'youtube'];
$count = 0;

foreach ($services as $s) {
    $nameLower = strtolower($s['name']);
    $catLower = strtolower($s['category'] ?? '');

    // Check if it's a target platform
    $platform = 'other';
    if (stripos($nameLower, 'instagram') !== false || stripos($catLower, 'instagram') !== false) {
        $platform = 'instagram';
    } elseif (stripos($nameLower, 'facebook') !== false || stripos($catLower, 'facebook') !== false) {
        $platform = 'facebook';
    } elseif (stripos($nameLower, 'tiktok') !== false) {
        $platform = 'tiktok';
    } elseif (stripos($nameLower, 'youtube') !== false || stripos($catLower, 'youtube') !== false) {
        $platform = 'youtube';
    }

    if (in_array($platform, $targetPlatforms)) {
        try {
            Database::query(
                "INSERT INTO services (smmwiz_id, platform, name, type, category, min_quantity, max_quantity, rate, our_rate, markup_percentage, status)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')",
                [$s['service'], $platform, $s['name'], $s['type'] ?? 'Default', $s['category'] ?? 'Other', $s['min'], $s['max'], $s['rate'], $s['rate'] * 1.20, 20.00]
            );
            $count++;
        } catch (Exception $e) {}
    }

    // Limit to prevent timeout
    if ($count >= 300) break;
}

echo "Synced $count services!\n\n";
echo "=== PRICES NOW ===\n";
$db = Database::fetchAll("SELECT platform, name, rate, our_rate FROM services ORDER BY platform, id LIMIT 20");
foreach ($db as $s) {
    echo "[{$s['platform']}] {$s['name']}\n  \${$s['rate']} → \${$s['our_rate']}\n\n";
}