<?php
/**
 * Create Admin User Script
 * Run this once to create the admin user
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain');

if (!isset($GLOBALS['db_available']) || !$GLOBALS['db_available']) {
    die("Database not available\n");
}

try {
    // Check if user already exists
    $existing = Database::fetch("SELECT id FROM users WHERE email = ?", ['harryroger798@gmail.com']);

    if ($existing) {
        // Update existing user to admin
        $password = password_hash('007JamesBond@@', PASSWORD_BCRYPT);
        Database::query(
            "UPDATE users SET username = 'admin2', password = ?, role = 'admin', is_active = true WHERE id = ?",
            [$password, $existing['id']]
        );
        echo "Updated user to admin!\n";
    } else {
        // Create new admin user
        $password = password_hash('007JamesBond@@', PASSWORD_BCRYPT);
        Database::query(
            "INSERT INTO users (email, username, password, role, balance, is_active) VALUES (?, ?, ?, 'admin', 0.00, true)",
            ['harryroger798@gmail.com', 'admin2', $password]
        );
        echo "Created new admin user!\n";
    }

    // Verify
    $user = Database::fetch("SELECT id, email, username, role FROM users WHERE email = ?", ['harryroger798@gmail.com']);
    print_r($user);

} catch (Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}