<?php
/**
 * SMM Panel - Main Entry Point
 * With graceful handling of database unavailability
 *
 * @version 1.2
 */

require_once __DIR__ . '/config.php';

// Check if database is available
if (!isset($GLOBALS['db_available']) || !$GLOBALS['db_available']) {
    // Show setup/install page
    header('Content-Type: text/html; charset=UTF-8');
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMM Panel - Setup</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; margin: 0; padding: 40px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #1e293b; margin-bottom: 20px; }
        .info { background: #f1f5f9; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .info h3 { margin-top: 0; color: #475569; }
        .info code { background: #e2e8f0; padding: 2px 6px; border-radius: 4px; }
        .env-var { margin: 10px 0; }
        .env-var label { display: block; font-weight: 600; color: #374151; margin-bottom: 4px; }
        .env-var code { display: block; background: #f1f5f9; padding: 8px; border-radius: 4px; font-size: 14px; }
        .note { color: #64748b; font-size: 14px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 SMM Panel - Setup Required</h1>
        <p>Welcome to your SMM Panel! To get started, you need to configure the database connection.</p>

        <div class="info">
            <h3>Current Status</h3>
            <p><strong>Database:</strong> <span style="color: #ef4444;">❌ Not Connected</span></p>
            <?php if (isset($GLOBALS['db_error'])): ?>
            <p><strong>Error:</strong> <?= htmlspecialchars($GLOBALS['db_error']) ?></p>
            <?php endif; ?>
        </div>

        <h3>Required Environment Variables</h3>
        <p>Add these in your Render dashboard (Environment Variables):</p>

        <div class="env-var">
            <label>DB_HOST</label>
            <code>10.60.139.53</code>
        </div>
        <div class="env-var">
            <label>DB_PORT</label>
            <code>3306</code>
        </div>
        <div class="env-var">
            <label>DB_NAME</label>
            <code>h6i63l3c0u</code>
        </div>
        <div class="env-var">
            <label>DB_USER</label>
            <code>h6i63l3c0u</code>
        </div>
        <div class="env-var">
            <label>DB_PASS</label>
            <code>8-Jos-!4xZ2p</code>
        </div>
        <div class="env-var">
            <label>SMMWIZ_API_KEY</label>
            <code>590d78f5f73a1e4a5816fa7c997e0bf6</code>
        </div>

        <p class="note">
            💡 After adding these variables, the service will automatically redeploy.<br>
            📝 Don't forget to import the database schema (schema.sql) to your MySQL!
        </p>
    </div>
</body>
</html>
    <?php
    exit;
}

// Continue with normal routing if DB is available
require_once __DIR__ . '/includes/helpers.php';

// Simple router for SEO-friendly URLs
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);

// Remove script name and query string from URI
$path = str_replace($scriptName, '', $requestUri);
$path = parse_url($path, PHP_URL_PATH);
$path = trim($path, '/');

// Route to appropriate page
$routes = [
    '' => 'pages/home.php',
    'index' => 'pages/home.php',
    'home' => 'pages/home.php',
    'services' => 'pages/services.php',
    'faq' => 'pages/faq.php',
    'terms' => 'pages/terms.php',
    'privacy' => 'pages/privacy.php',
    'login' => 'pages/login.php',
    'register' => 'pages/register.php',
    'dashboard' => 'pages/dashboard.php',
    'order' => 'pages/order.php',
    'api/services' => 'api/services.php',
    'api/order' => 'api/order.php',
    'api/status' => 'api/status.php',
    'api/balance' => 'api/balance.php',
];

// Default to home if route not found
$route = $routes[$path] ?? 'pages/home.php';

// Security: prevent directory traversal
$route = str_replace('..', '', $route);

if (file_exists(__DIR__ . '/' . $route)) {
    require_once __DIR__ . '/' . $route;
} else {
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
    echo "<p>The page you are looking for does not exist.</p>";
    exit;
}