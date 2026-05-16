<?php
/**
 * Database Setup Script
 * Run this once to initialize the database schema
 */

header('Content-Type: text/plain');

// Load config
require_once __DIR__ . '/config.php';

// Check if DB is available
if (!isset($GLOBALS['db_available']) || !$GLOBALS['db_available']) {
    die("Database not available\n");
}

try {
    $pdo = Database::getInstance();

    // Read schema file
    $schema = file_get_contents(__DIR__ . '/schema.sql');

    // Split by semicolons to get individual statements
    $statements = array_filter(array_map('trim', explode(';', $schema)));

    $count = 0;
    foreach ($statements as $sql) {
        if (empty($sql) || strpos($sql, '--') === 0) continue;

        try {
            $pdo->exec($sql);
            $count++;
            echo "Executed: " . substr($sql, 0, 50) . "...\n";
        } catch (Exception $e) {
            // Ignore duplicate key errors
            if (strpos($e->getMessage(), 'duplicate') === false) {
                echo "Error: " . $e->getMessage() . "\n";
            }
        }
    }

    echo "\nDone! Executed $count statements.\n";

    // Verify tables
    $tables = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name");
    echo "\nTables created: " . implode(', ', $tables->fetchAll(PDO::FETCH_COLUMN)) . "\n";

} catch (Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}