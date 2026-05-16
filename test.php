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
    echo "Contents: " . file_get_contents($envFile) . "\n";
} else {
    echo ".env does not exist\n";
}