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

    // Remove comments and split by semicolons
    $lines = explode("\n", $schema);
    $cleanSql = '';
    foreach ($lines as $line) {
        // Remove single-line comments
        $pos = strpos($line, '--');
        if ($pos !== false) {
            $line = substr($line, 0, $pos);
        }
        $cleanSql .= $line . "\n";
    }

    // Split by semicolons
    $statements = array_filter(array_map('trim', explode(';', $cleanSql)));

    $count = 0;
    $errors = 0;
    foreach ($statements as $sql) {
        if (empty($sql)) continue;

        try {
            $pdo->exec($sql);
            $count++;
        } catch (Exception $e) {
            $errors++;
            $msg = $e->getMessage();
            // Only show non-duplicate errors
            if (strpos($msg, 'duplicate') === false && strpos($msg, 'already exists') === false) {
                echo "Error: " . substr($sql, 0, 60) . "... - " . $msg . "\n";
            }
        }
    }

    echo "Executed $count statements with $errors warnings.\n";

    // Verify tables
    $tables = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name");
    $tableList = $tables->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables created: " . implode(', ', $tableList) . "\n";

    if (in_array('users', $tableList)) {
        echo "\nDatabase setup complete!\n";
    }

} catch (Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}