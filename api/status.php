<?php
/**
 * API: Check Order Status
 *
 * Endpoint: GET /api/status.php?id={order_id} or POST with order_id
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

// Get order ID from query or body
$orderId = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $orderId = $orderId ?? ($input['order_id'] ?? null);
}

if (empty($orderId)) {
    jsonError('Order ID is required', 400);
}

$orderId = (int) $orderId;

// Get current user
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    jsonError('Unauthorized', 401);
}

// Get order from database
$order = Database::fetch(
    "SELECT o.*, s.name as service_name, s.platform, s.refill as service_refill_support
     FROM orders o
     JOIN services s ON o.service_id = s.id
     WHERE o.id = ?",
    [$orderId]
);

if (!$order) {
    jsonError('Order not found', 404);
}

// Check access (admin can view any, users can only view their own)
if (!isAdmin() && (int) $order['user_id'] !== (int) $userId) {
    jsonError('Access denied', 403);
}

// Check if order has Smmwiz order ID
if (empty($order['smmwiz_order_id'])) {
    jsonError('Order not synced with provider', 400);
}

// Try to get status from Smmwiz
$smmwizClient = new SmmwizClient();
$smmwizStatus = null;
$smmwizError = null;

try {
    $smmwizStatus = $smmwizClient->status((int) $order['smmwiz_order_id']);
} catch (SmmwizException $e) {
    $smmwizError = $e->getMessage();
    // Log error but continue with local data
    logAction(
        $userId,
        'status_check_failed',
        ['order_id' => $orderId, 'smmwiz_order_id' => $order['smmwiz_order_id'], 'error' => $smmwizError],
        $e->getCode()
    );
}

// Map Smmwiz status to our status
$statusMapping = [
    'Pending' => 'pending',
    'In progress' => 'in_progress',
    'Partial' => 'partial',
    'Completed' => 'completed',
    'Cancelled' => 'cancelled',
    'Refunded' => 'refunded',
];

$newStatus = $order['status'];
if ($smmwizStatus && isset($smmwizStatus['status'])) {
    $smmwizStatusStr = ucfirst(strtolower($smmwizStatus['status']));
    $newStatus = $statusMapping[$smmwizStatusStr] ?? $order['status'];
}

// Update database if status changed
$updateData = [];
if ($smmwizStatus) {
    if (isset($smmwizStatus['start_count'])) {
        $updateData['start_count'] = $smmwizStatus['start_count'];
    }
    if (isset($smmwizStatus['remains'])) {
        $updateData['remains'] = $smmwizStatus['remains'];
    }

    // Calculate delivered quantity
    if (isset($smmwizStatus['start_count'], $smmwizStatus['remains'])) {
        $delivered = (int) $order['requested_quantity'] - (int) $smmwizStatus['remains'];
        $updateData['delivered_quantity'] = max(0, $delivered);
    }

    if ($newStatus !== $order['status']) {
        $updateData['status'] = $newStatus;
    }

    $updateData['api_response'] = json_encode($smmwizStatus);
}

if (!empty($updateData)) {
    Database::update('orders', $updateData, 'id = ?', [$orderId]);

    // Log status update
    logAction(
        $userId,
        'status_updated',
        [
            'order_id' => $orderId,
            'old_status' => $order['status'],
            'new_status' => $newStatus,
            'smmwiz_status' => $smmwizStatus['status'] ?? null
        ],
        200,
        'order',
        $orderId
    );
}

// Return response
$response = [
    'order_id' => (int) $order['id'],
    'smmwiz_order_id' => $order['smmwiz_order_id'],
    'service_name' => $order['service_name'],
    'platform' => $order['platform'],
    'link' => $order['link'],
    'quantity' => [
        'requested' => (int) $order['requested_quantity'],
        'delivered' => (int) ($updateData['delivered_quantity'] ?? $order['delivered_quantity']),
    ],
    'status' => $newStatus,
    'charge' => (float) $order['our_charge'],
    'created_at' => $order['created_at'],
    'updated_at' => $updateData['updated_at'] ?? $order['updated_at'],
];

if ($smmwizStatus) {
    $response['smmwiz'] = [
        'status' => $smmwizStatus['status'] ?? null,
        'start_count' => $smmwizStatus['start_count'] ?? null,
        'remains' => $smmwizStatus['remains'] ?? null,
    ];
}

if ($smmwizError) {
    $response['warning'] = 'Could not fetch live status: ' . $smmwizError;
}

jsonSuccess($response);