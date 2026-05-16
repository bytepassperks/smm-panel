<?php
/**
 * API: Create Order
 *
 * Endpoint: POST /api/order.php
 * Input: JSON { service_id, link, quantity, runs?, interval?, ... }
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

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

// Get request body
$input = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    jsonError('Invalid JSON input', 400);
}

// Validate required fields
$requiredFields = ['service_id', 'link', 'quantity'];
foreach ($requiredFields as $field) {
    if (empty($input[$field])) {
        jsonError("Missing required field: {$field}", 400);
    }
}

// Validate quantity
$quantity = (int) $input['quantity'];
if ($quantity <= 0) {
    jsonError('Invalid quantity', 400);
}

// Get current user (from session - implement actual auth)
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    jsonError('Unauthorized', 401);
}

// Get user from database
$user = Database::fetch("SELECT * FROM users WHERE id = ? AND is_active = true", [$userId]);

if (!$user) {
    jsonError('User not found or inactive', 401);
}

// Get service from database
$serviceId = (int) $input['service_id'];
$service = Database::fetch("SELECT * FROM services WHERE id = ? AND status = 'active'", [$serviceId]);

if (!$service) {
    jsonError('Service not found or inactive', 400);
}

// Calculate our charge
$ourCharge = calculatePrice($service['our_rate'], $quantity);

// Check user balance
if ((float) $user['balance'] < $ourCharge) {
    jsonError('Insufficient balance', 400, [
        'required' => $ourCharge,
        'available' => (float) $user['balance']
    ]);
}

// Validate quantity limits
if (!validateQuantity($quantity, (int) $service['min_quantity'], (int) $service['max_quantity'])) {
    jsonError('Quantity outside service limits', 400, [
        'min' => $service['min_quantity'],
        'max' => $service['max_quantity']
    ]);
}

// Validate link format
$platform = $service['platform'];
if (!validateSocialLink($input['link'], $platform)) {
    jsonError('Invalid ' . ucfirst($platform) . ' link format', 400);
}

// Try to create order with Smmwiz
$smmwizClient = new SmmwizClient();
$smmwizOrderId = null;
$smmwizCharge = 0;
$smmwizError = null;

try {
    $orderData = [
        'service' => $service['smmwiz_id'],
        'link' => $input['link'],
        'quantity' => $quantity,
    ];

    // Add optional fields
    if (!empty($input['runs'])) {
        $orderData['runs'] = (int) $input['runs'];
    }
    if (!empty($input['interval'])) {
        $orderData['interval'] = (int) $input['interval'];
    }
    if (!empty($input['custom_comments'])) {
        $orderData['custom_comments'] = $input['custom_comments'];
    }
    if (!empty($input['mentions'])) {
        $orderData['mentions'] = $input['mentions'];
    }

    $smmwizResponse = $smmwizClient->order($orderData);

    $smmwizOrderId = $smmwizResponse['order_id'];
    $smmwizCharge = $smmwizResponse['charge'] ?? 0;

} catch (SmmwizException $e) {
    $smmwizError = $e->getMessage();

    // Log the error
    logAction(
        $userId,
        'order_failed',
        [
            'service_id' => $serviceId,
            'link' => $input['link'],
            'quantity' => $quantity,
            'error' => $smmwizError
        ],
        $e->getCode(),
        'order',
        null
    );

    // If Smmwiz is down, we might still want to create order with pending status
    // For now, fail the order
    jsonError('Smmwiz API error: ' . $smmwizError, 503);
}

// Calculate profit
$profit = $ourCharge - $smmwizCharge;

// Start transaction
Database::beginTransaction();

try {
    // Deduct user balance
    updateBalance($userId, $ourCharge, 'subtract');

    // Create order record
    $orderId = Database::insert('orders', [
        'user_id' => $userId,
        'service_id' => $serviceId,
        'smmwiz_order_id' => $smmwizOrderId,
        'smmwiz_service_id' => $service['smmwiz_id'],
        'service_name' => $service['name'],
        'link' => $input['link'],
        'requested_quantity' => $quantity,
        'delivered_quantity' => 0,
        'status' => 'pending',
        'charge_smw' => $smmwizCharge,
        'our_charge' => $ourCharge,
        'profit' => $profit,
        'api_response' => json_encode($smmwizResponse),
    ]);

    // Commit transaction
    Database::commit();

    // Log successful order
    logAction(
        $userId,
        'order_created',
        [
            'order_id' => $orderId,
            'smmwiz_order_id' => $smmwizOrderId,
            'service_id' => $serviceId,
            'quantity' => $quantity,
            'charge' => $ourCharge
        ],
        200,
        'order',
        $orderId
    );

    // Return success response
    jsonSuccess([
        'order_id' => (int) $orderId,
        'smmwiz_order_id' => $smmwizOrderId,
        'service_name' => $service['name'],
        'link' => $input['link'],
        'quantity' => $quantity,
        'status' => 'pending',
        'charge' => $ourCharge,
        'remaining_balance' => (float) $user['balance'] - $ourCharge,
    ], 201);

} catch (Exception $e) {
    // Rollback transaction
    Database::rollback();

    // Log error
    logAction(
        $userId,
        'order_creation_failed',
        [
            'service_id' => $serviceId,
            'error' => $e->getMessage()
        ],
        500
    );

    jsonError('Failed to create order: ' . $e->getMessage(), 500);
}