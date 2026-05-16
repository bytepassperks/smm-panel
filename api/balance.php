<?php
/**
 * API: Get User Balance
 *
 * Endpoint: GET /api/balance.php
 *
 * @version 1.0.0
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/SmmwizClient.php';
require_once __DIR__ . '/../includes/helpers.php';

// Set headers
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

// Get current user
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    jsonError('Unauthorized', 401);
}

// Get user from database
$user = Database::fetch(
    "SELECT id, email, username, role, balance FROM users WHERE id = ? AND is_active = true",
    [$userId]
);

if (!$user) {
    jsonError('User not found or inactive', 401);
}

// Get Smmwiz balance (optional - can be disabled for performance)
$smmwizBalance = null;
$showSmmwizBalance = getenv('SHOW_SMMWIZ_BALANCE') === 'true';

if ($showSmmwizBalance) {
    try {
        $smmwizClient = new SmmwizClient();
        $balanceResponse = $smmwizClient->balance();
        $smmwizBalance = [
            'balance' => $balanceResponse['balance'],
            'currency' => $balanceResponse['currency'] ?? 'USD',
        ];
    } catch (SmmwizException $e) {
        // Log but don't fail - Smmwiz might be down
        logAction(
            $userId,
            'smmwiz_balance_failed',
            ['error' => $e->getMessage()],
            $e->getCode()
        );
    }
}

// Get recent transactions (last 10)
$transactions = Database::fetchAll(
    "SELECT id, action, payload, http_status, created_at
     FROM logs
     WHERE user_id = ? AND action IN ('order_created', 'balance_added', 'balance_deducted', 'refund')
     ORDER BY created_at DESC
     LIMIT 10",
    [$userId]
);

// Format transactions
$formattedTransactions = array_map(function ($t) {
    $payload = $t['payload'] ? json_decode($t['payload'], true) : [];
    return [
        'id' => $t['id'],
        'action' => $t['action'],
        'amount' => $payload['charge'] ?? $payload['amount'] ?? null,
        'created_at' => $t['created_at'],
    ];
}, $transactions);

// Build response
$response = [
    'user' => [
        'id' => (int) $user['id'],
        'email' => $user['email'],
        'username' => $user['username'],
        'role' => $user['role'],
    ],
    'balance' => [
        'amount' => (float) $user['balance'],
        'currency' => 'USD',
    ],
    'transactions' => $formattedTransactions,
];

if ($smmwizBalance) {
    $response['smmwiz_balance'] = $smmwizBalance;
}

jsonSuccess($response);