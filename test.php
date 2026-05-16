<?php
// Simple test to check PHP environment
header('Content-Type: text/plain');

echo "PHP Version: " . PHP_VERSION . "\n";
echo "PDO Drivers: " . implode(', ', PDO::getAvailableDrivers()) . "\n";
echo "\nEnvironment variables:\n";
echo "DB_HOST: " . getenv('DB_HOST') . "\n";
echo "DB_NAME: " . getenv('DB_NAME') . "\n";
echo "SMMWIZ_API_KEY: " . (getenv('SMMWIZ_API_KEY') ? 'set' : 'not set') . "\n";

echo "\n.env file check:\n";
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    echo ".env exists\n";
} else {
    echo ".env does not exist\n";
}

echo "\nTrying database connection:\n";
try {
    $dsn = sprintf(
        'pgsql:host=%s;port=%d;dbname=%s',
        getenv('DB_HOST') ?: 'localhost',
        (int)(getenv('DB_PORT') ?: 5432),
        getenv('DB_NAME') ?: 'smm_panel'
    );
    echo "DSN: $dsn\n";
    $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'));
    echo "Database connected successfully!\n";

    // Test a simple query
    $result = $pdo->query("SELECT version()");
    echo "PostgreSQL version: " . $result->fetchColumn() . "\n";

    // Check if tables exist
    $tables = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    echo "Tables: " . implode(', ', $tables->fetchAll(PDO::FETCH_COLUMN)) . "\n";
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}