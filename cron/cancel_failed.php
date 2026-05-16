<?php
/**
 * Cron: Cancel Failed Orders
 *
 * Optionally cancel orders that have been stuck in 'cancelled' status
 * on Smmwiz side for more than 24 hours.
 *
 * Usage: php cancel_failed.php
 * Cron: 0 * * * * cd /var/www/html && php cron/cancel_failed.php
 *
 * @version 1.0.0
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/SmmwizClient.php';

echo "[" . date('Y-m-d H:i:s') . "] Starting failed order cancellation check...\n";

// Find orders that are still pending but older than 24 hours
$stuckOrders = Database::fetchAll(
    "SELECT id, smmwiz_order_id, user_id, service_id, our_charge
     FROM orders
     WHERE status = 'pending'
       AND smmwiz_order_id IS NOT NULL
       AND created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)
     LIMIT 100"
);

if (empty($stuckOrders)) {
    echo "[" . date('Y-m-d H:i:s') . "] No stuck orders found.\n";
    exit(0);
}

echo "[" . date('Y-m-d H:i:s') . "] Found " . count($stuckOrders) . " stuck orders.\n";

$smmwizClient = new SmmwizClient();
$cancelledCount = 0;
$refundedCount = 0;

foreach ($stuckOrders as $order) {
    try {
        // Check current status
        $statusResponse = $smmwizClient->status((int) $order['smmwiz_order_id']);

        $smmwizStatus = strtolower($statusResponse['status'] ?? '');

        // If Smmwiz shows cancelled, update our DB
        if (in_array($smmwizStatus, ['cancelled', 'canceled', 'error'])) {
            Database::update(
                'orders',
                ['status' => 'cancelled', 'api_response' => json_encode($statusResponse)],
                'id = ?',
                [$order['id']]
            );

            // Optionally refund the user
            // updateBalance($order['user_id'], $order['our_charge'], 'add');
            // $refundedCount++;

            $cancelledCount++;

            logAction(
                $order['user_id'],
                'cron_order_cancelled',
                ['order_id' => $order['id'], 'reason' => 'Smmwiz shows cancelled status'],
                200,
                'order',
                $order['id']
            );
        }

    } catch (SmmwizException $e) {
        echo "[" . date('Y-m-d H:i:s') . "] ERROR checking order {$order['smmwiz_order_id']}: " . $e->getMessage() . "\n";
    }
}

echo "[" . date('Y-m-d H:i:s') . "] Cancelled: {$cancelledCount}, Refunded: {$refundedCount}\n";
exit(0);