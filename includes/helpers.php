<?php
/**
 * Helper Functions
 * Common utility functions for the SMM Panel
 *
 * @version 1.0.0
 */

// =====================================================
// SECURITY HELPERS
// =====================================================

/**
 * Generate CSRF token
 *
 * @return string
 */
function csrfToken(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }

    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Validate CSRF token
 *
 * @param string $token
 * @return bool
 */
function validateCsrfToken(string $token): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Sanitize input
 *
 * @param mixed $input
 * @return mixed
 */
function sanitize(mixed $input)
{
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }

    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email address
 *
 * @param string $email
 * @return bool
 */
function isValidEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate URL
 *
 * @param string $url
 * @return bool
 */
function isValidUrl(string $url): bool
{
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

// =====================================================
// PASSWORD HELPERS (bcrypt)
// =====================================================

/**
 * Hash password using bcrypt
 *
 * @param string $password
 * @return string
 */
function hashPassword(string $password): string
{
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Verify password
 *
 * @param string $password
 * @param string $hash
 * @return bool
 */
function verifyPassword(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

// =====================================================
// USER HELPERS
// =====================================================

/**
 * Get current logged in user
 *
 * @return array|null
 */
function currentUser(): ?array
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    return Database::fetch(
        "SELECT id, email, username, role, balance FROM users WHERE id = ? AND is_active = 1",
        [$_SESSION['user_id']]
    );
}

/**
 * Check if user is logged in
 *
 * @return bool
 */
function isLoggedIn(): bool
{
    return currentUser() !== null;
}

/**
 * Check if user has specific role
 *
 * @param string $role
 * @return bool
 */
function userHasRole(string $role): bool
{
    $user = currentUser();
    return $user && $user['role'] === $role;
}

/**
 * Check if user is admin
 *
 * @return bool
 */
function isAdmin(): bool
{
    return userHasRole('admin');
}

/**
 * Check if user is reseller
 *
 * @return bool
 */
function isReseller(): bool
{
    return userHasRole('reseller');
}

// =====================================================
// BALANCE HELPERS
// =====================================================

/**
 * Calculate order price with markup
 *
 * @param float $smmwizRate
 * @param int $quantity
 * @param float $markupPercent
 * @return float
 */
function calculatePrice(float $smmwizRate, int $quantity, float $markupPercent = DEFAULT_MARKUP): float
{
    $basePrice = $smmwizRate * $quantity;
    $markup = $basePrice * ($markupPercent / 100);
    return round($basePrice + $markup, 2);
}

/**
 * Update user balance
 *
 * @param int $userId
 * @param float $amount
 * @param string $operation - 'add' or 'subtract'
 * @return bool
 */
function updateBalance(int $userId, float $amount, string $operation = 'add'): bool
{
    if ($operation === 'add') {
        $sql = "UPDATE users SET balance = balance + ? WHERE id = ?";
    } else {
        $sql = "UPDATE users SET balance = balance - ? WHERE id = ?";
    }

    Database::query($sql, [$amount, $userId]);
    return true;
}

/**
 * Check user has sufficient balance
 *
 * @param int $userId
 * @param float $amount
 * @return bool
 */
function hasSufficientBalance(int $userId, float $amount): bool
{
    $balance = Database::getValue('users', 'balance', 'id = ?', [$userId]);
    return (float) $balance >= $amount;
}

// =====================================================
// LOGGING HELPERS
// =====================================================

/**
 * Log user action
 *
 * @param int|null $userId
 * @param string $action
 * @param array|null $payload
 * @param int|null $httpStatus
 * @param string|null $entityType
 * @param int|null $entityId
 */
function logAction(
    ?int $userId,
    string $action,
    ?array $payload = null,
    ?int $httpStatus = null,
    ?string $entityType = null,
    ?int $entityId = null
): void {
    $data = [
        'user_id' => $userId,
        'action' => $action,
        'entity_type' => $entityType,
        'entity_id' => $entityId,
        'payload' => $payload ? json_encode($payload) : null,
        'http_status' => $httpStatus,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
    ];

    try {
        Database::insert('logs', $data);
    } catch (Exception $e) {
        error_log("Failed to log action: " . $e->getMessage());
    }
}

// =====================================================
// RESPONSE HELPERS
// =====================================================

/**
 * Send JSON response
 *
 * @param mixed $data
 * @param int $statusCode
 * @return void
 */
function jsonResponse(mixed $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Send success JSON response
 *
 * @param mixed $data
 * @param int $statusCode
 */
function jsonSuccess(mixed $data = [], int $statusCode = 200): void
{
    jsonResponse([
        'success' => true,
        'data' => $data,
    ], $statusCode);
}

/**
 * Send error JSON response
 *
 * @param string $message
 * @param int $statusCode
 * @param mixed $errors
 */
function jsonError(string $message, int $statusCode = 400, $errors = null): void
{
    jsonResponse([
        'success' => false,
        'error' => $message,
        'errors' => $errors,
    ], $statusCode);
}

// =====================================================
// DATE/TIME HELPERS
// =====================================================

/**
 * Format datetime
 *
 * @param string|null $datetime
 * @param string $format
 * @return string
 */
function formatDateTime(?string $datetime, string = 'd M Y, h:i A'): string
{
    if (!$datetime) {
        return 'N/A';
    }

    $date = new DateTime($datetime);
    return $date->format($format);
}

/**
 * Get time ago string
 *
 * @param string $datetime
 * @return string
 */
function timeAgo(string $datetime): string
{
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return $diff . ' seconds ago';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' minutes ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' hours ago';
    } elseif ($diff < 2592000) {
        return floor($diff / 86400) . ' days ago';
    } else {
        return date('d M Y', $timestamp);
    }
}

// =====================================================
// VALIDATION HELPERS
// =====================================================

/**
 * Validate quantity is within service limits
 *
 * @param int $quantity
 * @param int $min
 * @param int $max
 * @return bool
 */
function validateQuantity(int $quantity, int $min, int $max): bool
{
    return $quantity >= $min && $quantity <= $max;
}

/**
 * Validate social media link
 *
 * @param string $link
 * @param string $platform
 * @return bool
 */
function validateSocialLink(string $link, string $platform): bool
{
    $patterns = [
        'instagram' => '/^(https?:\/\/)?(www\.)?instagram\.com\/[a-zA-Z0-9._-]+\/?$/i',
        'facebook' => '/^(https?:\/\/)?(www\.)?(facebook\.com|fb\.com)\/[a-zA-Z0-9.-]+\/?$/i',
        'tiktok' => '/^(https?:\/\/)?(www\.)?tiktok\.com\/@[a-zA-Z0-9._-]+\/?$/i',
        'youtube' => '/^(https?:\/\/)?(www\.)?youtube\.com\/(channel\/[a-zA-Z0-9_-]+|@[\w-]+|v\/[\w-]+)\/?$/i',
    ];

    $pattern = $patterns[$platform] ?? '/.+/';

    return preg_match($pattern, $link) === 1;
}

// =====================================================
// SEO HELPERS
// =====================================================

/**
 * Generate meta description
 *
 * @param string $text
 * @param int $length
 * @return string
 */
function metaDescription(string $text, int $length = 160): string
{
    $text = strip_tags($text);
    $text = trim($text);

    if (mb_strlen($text) <= $length) {
        return $text;
    }

    return mb_substr($text, 0, $length - 3) . '...';
}

/**
 * Generate JSON-LD script tag
 *
 * @param array $data
 * @return string
 */
function jsonLd(array $data): string
{
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES) . '</script>';
}