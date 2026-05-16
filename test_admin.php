<?php
// Test admin page
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/config.php';

echo "Config loaded\n";
echo "DB available: " . ($GLOBALS['db_available'] ?? 'no') . "\n";
echo "SESSION: " . (session_status() === PHP_SESSION_ACTIVE ? 'active' : 'inactive') . "\n";

try {
    $users = Database::fetchAll("SELECT id, username, role FROM users WHERE role = 'admin' LIMIT 5");
    echo "Admins found: " . count($users) . "\n";
    print_r($users);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}