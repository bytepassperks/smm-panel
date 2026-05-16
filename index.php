<?php
/**
 * SMM Panel - Main Entry Point
 * Route all requests to appropriate pages
 *
 * @version 1.0
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/helpers.php';

// Simple router for SEO-friendly URLs
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);

// Remove script name from URI
$path = str_replace($scriptName, '', $requestUri);
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