<?php
/**
 * Cron: Sync Orders
 *
 * Run this script periodically (e.g., every 5 minutes) to sync order
 * statuses with Smmwiz API using multiStatus endpoint.
 *
 * Usage: php sync_orders.php
 * Cron: */5 * * * * cd /var/www/html && php cron/sync_orders.php
 *
 * @version 1.0.0
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/SmmwizClient.php';

echo "[" . date('Y-m-d H:i:s') . "] Starting order sync...\n";

// Configuration
$batchSize = 50; // Process 50 orders at a time
$statusesToSync = ['pending', 'in_progress', 'partial']; // Only sync active orders
$statusMapping = [
    'Pending' => 'pending',
    'In progress' => 'in_progress',
    'Partial' => 'partial',
    'Completed' => 'completed',
    'Cancelled' => 'cancelled',
    'Refunded' => 'refunded',
];

// Get orders that need syncing
$orders = Database::fetchAll(
    "SELECT id, smmwiz_order_id, user_id, service_id, requested_quantity, status
     FROM orders
     WHERE smmwiz_order_id IS NOT NULL
       AND smmwiz_order_id > 0
       AND status IN ('" . implode("','", $statusesToSync) . "')
     ORDER BY created_at ASC
     LIMIT ?",
    [$batchSize]
);

if (empty($orders)) {
    echo "[" . date('Y-m-d H:i:s') . "] No orders to sync.\n";
    exit(0);
}

echo "[" . date('Y-m-d H:i:s') . "] Found " . count($orders) . " orders to sync.\n";

// Extract order IDs for multiStatus
$smmwizOrderIds = array_column($orders, 'smmwiz_order_id');

// Fetch statuses from Smmwiz
$smmwizClient = new SmmwizClient();
$statusResults = [];

try {
    $smmwizResponse = $smmwizClient->multiStatus($smmwizOrderIds);
    $statusResults = $smmwizResponse; // Array of order statuses
    echo "[" . date('Y-m-d H:i:s') . "] Got " . count($statusResults) . " status updates from Smmwiz.\n";
} catch (SmmwizException $e) {
    echo "[" . date('Y-m-d H:i:s') . "] ERROR: Smmwiz API error: " . $e->getMessage() . "\n";
    logAction(null, 'cron_sync_failed', ['error' => $e->getMessage()], 0);
    exit(1);
}

// Create lookup by Smmwiz order ID
$statusLookup = [];
foreach ($statusResults as $result) {
    if (isset($result['order_id'])) {
        $statusLookup[$result['order_id']] = $result;
    }
}

// Process each order
$updatedCount = 0;
$completedCount = 0;

foreach ($orders as $order) {
    $smmwizOrderId = $order['smmwiz_order_id'];

    if (!isset($statusLookup[$smmwizOrderId])) {
        continue; // Skip if no status returned
    }

    $statusData = $statusLookup[$smmwizOrderId];
    $newStatus = $statusMapping[$statusData['status']] ?? $order['status'];

    // Calculate delivered quantity
    $delivered = 0;
    if (isset($statusData['remains'])) {
        $delivered = (int) $order['requested_quantity'] - (int) $statusData['remains'];
        $delivered = max(0, $delivered);
    }

    // Check if we need to trigger refill
    $triggerRefill = false;
    if ($order['status'] === 'partial' && $delivered > (int) $order['delivered_quantity']) {
        $triggerRefill = true;
    }

    // Update order in database
    $updateData = [
        'delivered_quantity' => $delivered,
        'remains' => $statusData['remains'] ?? null,
        'status' => $newStatus,
        'api_response' => json_encode($statusData),
    ];

    Database::update('orders', $updateData, 'id = ?', [$order['id']]);

    $updatedCount++;

    if ($newStatus === 'completed') {
        $completedCount++;
    }

    // Log status update
    logAction(
        $order['user_id'],
        'cron_status_sync',
        [
            'order_id' => $order['id'],
            'smmwiz_order_id' => $smmwizOrderId,
            'old_status' => $order['status'],
            'new_status' => $newStatus,
            'delivered' => $delivered
        ],
        200,
        'order',
        $order['id']
    );

    // Check if we should trigger automatic refill
    $service = Database::fetch("SELECT refill FROM services WHERE id = ?", [$order['service_id']]);
    if ($triggerRefill && !empty($service['refill'])) {
        try {
            $refillResponse = $smmwizClient->refill((int) $smmwizOrderId);

            // Save refill ID
            $refillId = $refillResponse['refill_id'] ?? null;
            if ($refillId) {
                $existingRefills = json_decode($order['refill_ids'] ?? '[]', true) ?: [];
                $existingRefills[] = $refillId;

                Database::update(
                    'orders',
                    [
                        'refill_ids' => json_encode($existingRefills),
                        'refill_count' => (int) $order['refill_count'] + 1,
                        'last_refill_at' => date('Y-m-d H:i:s'),
                        'status' => 'refilled'
                    ],
                    'id = ?',
                    [$order['id']]
                );

                echo "[" . date('Y-m-d H:i:s') . "] Triggered refill for order {$order['id']}, refill ID: {$refillId}\n";
            }
        } catch (SmmwizException $e) {
            echo "[" . date('Y-m-d H:i:s') . "] ERROR: Refill failed for order {$order['id']}: " . $e->getMessage() . "\n";
        }
    }
}

echo "[" . date('Y-m-d H:i:s') . "] Sync completed. Updated: {$updatedCount}, Completed: {$completedCount}\n";

// Log cron completion
logAction(null, 'cron_sync_completed', [
    'processed' => count($orders),
    'updated' => $updatedCount,
    'completed' => $completedCount
], 200);

exit(0);