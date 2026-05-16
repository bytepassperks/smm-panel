<?php
/**
 * Admin - Manage Orders
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';

session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /admin/login');
    exit;
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_status'])) {
        $id = (int)$_POST['id'];
        $status = $_POST['status'];
        Database::query("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?", [$status, $id]);
        $message = "Order status updated!";
    }

    if (isset($_POST['sync_status'])) {
        require_once __DIR__ . '/../../includes/SmmwizClient.php';
        $client = new SmmwizClient(SMMWIZ_API_KEY, SMMWIZ_API_URL);

        // Get pending/in_progress orders
        $orders = Database::fetchAll(
            "SELECT * FROM orders WHERE status IN ('pending', 'in_progress') AND smmwiz_order_id IS NOT NULL LIMIT 50"
        );

        $synced = 0;
        foreach ($orders as $order) {
            $result = $client->status($order['smmwiz_order_id']);
            if (!empty($result['order'])) {
                $smmwizOrder = $result['order'];
                Database::query(
                    "UPDATE orders SET status = ?, delivered_quantity = ?, updated_at = NOW() WHERE id = ?",
                    [$smmwizOrder['status'], $smmwizOrder['charges'] ?? 0, $order['id']]
                );
                $synced++;
            }
        }
        $message = "Synced $synced orders from Smmwiz!";
    }
}

$statusFilter = $_GET['status'] ?? '';
$where = '1=1';
$params = [];
if ($statusFilter) {
    $where .= " AND o.status = ?";
    $params[] = $statusFilter;
}

$orders = Database::fetchAll(
    "SELECT o.*, u.username, s.name as service_name
     FROM orders o
     LEFT JOIN users u ON o.user_id = u.id
     LEFT JOIN services s ON o.service_id = s.id
     WHERE $where
     ORDER BY o.created_at DESC
     LIMIT 100",
    $params
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Admin</title>
    <link rel="stylesheet" href="https://smm-panel-5lqp.onrender.com/assets/css/index.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .admin-header { background: #1e293b; color: white; padding: 20px; }
        .admin-header .container { display: flex; justify-content: space-between; align-items: center; max-width: 1400px; margin: 0 auto; }
        .admin-header h1 { font-size: 24px; }
        .admin-nav { display: flex; gap: 20px; }
        .admin-nav a { color: #94a3b8; text-decoration: none; padding: 8px 16px; border-radius: 6px; }
        .admin-nav a:hover, .admin-nav a.active { background: #334155; color: white; }
        .admin-nav a.logout { color: #f87171; }
        .container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; }
        .btn-primary { background: #6366f1; color: white; }
        .btn-success { background: #10b981; color: white; }
        .message { background: #d1fae5; color: #065f46; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; }
        .filters { display: flex; gap: 10px; margin-bottom: 20px; }
        .filters select { padding: 10px 16px; border: 1px solid #d1d5db; border-radius: 8px; }
        table { width: 100%; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { background: #f1f5f9; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; }
        tr:hover { background: #f8fafc; }
        .status-badge { padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-in_progress { background: #dbeafe; color: #2563eb; }
        .status-completed { background: #d1fae5; color: #059669; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
        .status-refunded { background: #f3e8ff; color: #9333ea; }
        .status-partial { background: #fef3c7; color: #d97706; }
        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 4px; border: none; cursor: pointer; }
        .btn-sm-primary { background: #6366f1; color: white; }
        .form-inline { display: flex; gap: 8px; align-items: center; }
        .form-inline select { padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; }
        .link { color: #6366f1; text-decoration: none; word-break: break-all; font-size: 12px; }
        .link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <h1>⚙️ Admin Dashboard</h1>
            <nav class="admin-nav">
                <a href="/admin/dashboard">Dashboard</a>
                <a href="/admin/services">Services</a>
                <a href="/admin/users">Users</a>
                <a href="/admin/orders" class="active">Orders</a>
                <a href="/admin/settings">Settings</a>
                <a href="/admin/logout" class="logout">Logout</a>
            </nav>
        </div>
    </div>

    <div class="container">
        <?php if (isset($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="toolbar">
            <h2>Manage Orders</h2>
            <form method="POST" style="display:inline;">
                <button type="submit" name="sync_status" class="btn btn-success">🔄 Sync from Smmwiz</button>
            </form>
        </div>

        <div class="filters">
            <form method="GET">
                <select name="status" onchange="this.form.submit()">
                    <option value="">All Orders</option>
                    <option value="pending" <?= $statusFilter=='pending'?'selected':'' ?>>Pending</option>
                    <option value="in_progress" <?= $statusFilter=='in_progress'?'selected':'' ?>>In Progress</option>
                    <option value="completed" <?= $statusFilter=='completed'?'selected':'' ?>>Completed</option>
                    <option value="partial" <?= $statusFilter=='partial'?'selected':'' ?>>Partial</option>
                    <option value="cancelled" <?= $statusFilter=='cancelled'?'selected':'' ?>>Cancelled</option>
                </select>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Service</th>
                    <th>Link</th>
                    <th>Qty</th>
                    <th>Delivered</th>
                    <th>Amount</th>
                    <th>Profit</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <td>#<?= $o['id'] ?></td>
                    <td><?= htmlspecialchars($o['username'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($o['service_name'] ?? 'N/A') ?></td>
                    <td><a href="<?= htmlspecialchars($o['link']) ?>" class="link" target="_blank">View</a></td>
                    <td><?= number_format($o['requested_quantity']) ?></td>
                    <td><?= number_format($o['delivered_quantity']) ?></td>
                    <td>$<?= number_format($o['charge_smw'], 2) ?></td>
                    <td style="color: #10b981; font-weight: 600;">$<?= number_format($o['profit'], 2) ?></td>
                    <td><span class="status-badge status-<?= str_replace(' ', '_', $o['status']) ?>"><?= $o['status'] ?></span></td>
                    <td><?= date('d M, h:i A', strtotime($o['created_at'])) ?></td>
                    <td>
                        <form method="POST" class="form-inline">
                            <input type="hidden" name="id" value="<?= $o['id'] ?>">
                            <input type="hidden" name="update_status" value="1">
                            <select name="status">
                                <option value="pending" <?= $o['status']=='pending'?'selected':'' ?>>Pending</option>
                                <option value="in_progress" <?= $o['status']=='in_progress'?'selected':'' ?>>In Progress</option>
                                <option value="completed" <?= $o['status']=='completed'?'selected':'' ?>>Completed</option>
                                <option value="partial" <?= $o['status']=='partial'?'selected':'' ?>>Partial</option>
                                <option value="cancelled" <?= $o['status']=='cancelled'?'selected':'' ?>>Cancelled</option>
                                <option value="refunded" <?= $o['status']=='refunded'?'selected':'' ?>>Refunded</option>
                            </select>
                            <button type="submit" class="btn-sm btn-sm-primary">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($orders)): ?>
                <tr><td colspan="11" style="text-align: center; color: #94a3b8; padding: 40px;">No orders found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>