<?php
/**
 * Configuration Loader
 * Loads environment variables and sets up site configuration
 *
 * @version 1.0.0
 */

// Load .env file if exists (for local development)
function loadEnvFile(string $filePath): void
{
    if (!file_exists($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Remove quotes if present
            if (preg_match('/^"(.*)"$/', $value, $matches)) {
                $value = $matches[1];
            } elseif (preg_match("/^'(.*)'$/", $value, $matches)) {
                $value = $matches[1];
            }

            $_ENV[$key] = $value;
            putenv("{$key}={$value}");
        }
    }
}

// Load .env from multiple possible locations
loadEnvFile(__DIR__ . '/.env');
loadEnvFile(__DIR__ . '/../.env');
loadEnvFile(__DIR__ . '/../../.env');

// =====================================================
// DATABASE CONFIGURATION
// =====================================================
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'smm_panel');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// =====================================================
// SMMWIZ API CONFIGURATION
// =====================================================
define('SMMWIZ_API_KEY', getenv('SMMWIZ_API_KEY') ?: '590d78f5f73a1e4a5816fa7c997e0bf6');
define('SMMWIZ_API_URL', getenv('SMMWIZ_API_URL') ?: 'https://smmwiz.com/api/v2');

// =====================================================
// SITE CONFIGURATION
// =====================================================
define('SITE_NAME', getenv('SITE_NAME') ?: 'SMM Panel');
define('SITE_URL', getenv('SITE_URL') ?: 'https://your-domain.com');
define('SITE_EMAIL', getenv('SITE_EMAIL') ?: 'support@yourdomain.com');

// Default markup percentage for services (can be overridden per service)
define('DEFAULT_MARKUP', getenv('DEFAULT_MARKUP') ?: 20.00);

// =====================================================
// SECURITY CONFIGURATION
// =====================================================
define('SESSION_NAME', 'SMMPANEL_SESSION');
define('CSRF_TOKEN_NAME', 'csrf_token');
define('JWT_SECRET', getenv('JWT_SECRET') ?: 'change-this-in-production');

// =====================================================
// TIMEZONE
// =====================================================
date_default_timezone_set('Asia/Kolkata');

// =====================================================
// ERROR REPORTING (Disable in production)
// =====================================================
if (getenv('APP_ENV') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// =====================================================
// AUTO-LOAD CLASSES
// =====================================================
spl_autoload_register(function (string $class): void {
    $file = __DIR__ . '/includes/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Initialize database connection (graceful - don't crash if DB unavailable)
$GLOBALS['db_available'] = false;
try {
    require_once __DIR__ . '/includes/db.php';
    Database::getInstance();
    $GLOBALS['db_available'] = true;
} catch (Exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    $GLOBALS['db_error'] = $e->getMessage();
}