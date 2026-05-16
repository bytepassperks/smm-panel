<?php
/**
 * Admin Dashboard - Main Hub
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';

// Check admin authentication
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /admin/login');
    exit;
}

// Get stats
$stats = [
    'users' => Database::count('users'),
    'orders' => Database::count('orders'),
    'services' => Database::count('services', "status = 'active'"),
    'revenue' => Database::fetch("SELECT SUM(charge_smw) as total FROM orders")['total'] ?? 0,
];

$recentOrders = Database::fetchAll(
    "SELECT o.*, u.username, s.name as service_name
     FROM orders o
     LEFT JOIN users u ON o.user_id = u.id
     LEFT JOIN services s ON o.service_id = s.id
     ORDER BY o.created_at DESC
     LIMIT 10"
);

$recentUsers = Database::fetchAll(
    "SELECT id, username, email, role, balance, created_at
     FROM users
     ORDER BY created_at DESC
     LIMIT 5"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SMM Panel</title>
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
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 24px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .stat-card h3 { color: #64748b; font-size: 14px; margin-bottom: 8px; }
        .stat-card .value { font-size: 32px; font-weight: 700; color: #1e293b; }
        .stat-card.revenue .value { color: #10b981; }
        .dashboard-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .panel { background: white; padding: 24px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .panel h2 { font-size: 18px; margin-bottom: 16px; color: #1e293b; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .table th { font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-completed { background: #d1fae5; color: #059669; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
        .empty { color: #94a3b8; text-align: center; padding: 40px; }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <h1>⚙️ Admin Dashboard</h1>
            <nav class="admin-nav">
                <a href="/admin/dashboard" class="active">Dashboard</a>
                <a href="/admin/services">Services</a>
                <a href="/admin/users">Users</a>
                <a href="/admin/orders">Orders</a>
                <a href="/admin/settings">Settings</a>
                <a href="/admin/logout" class="logout">Logout</a>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="value"><?= number_format($stats['users']) ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Orders</h3>
                <div class="value"><?= number_format($stats['orders']) ?></div>
            </div>
            <div class="stat-card">
                <h3>Active Services</h3>
                <div class="value"><?= number_format($stats['services']) ?></div>
            </div>
            <div class="stat-card revenue">
                <h3>Total Revenue</h3>
                <div class="value">$<?= number_format($stats['revenue'], 2) ?></div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="panel">
                <h2>Recent Orders</h2>
                <?php if (empty($recentOrders)): ?>
                <p class="empty">No orders yet</p>
                <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User</th>
                            <th>Service</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['username'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($order['service_name'] ?? 'N/A') ?></td>
                            <td>$<?= number_format($order['charge_smw'], 2) ?></td>
                            <td><span class="status-badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>

            <div class="panel">
                <h2>Recent Users</h2>
                <?php if (empty($recentUsers)): ?>
                <p class="empty">No users yet</p>
                <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentUsers as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><span class="status-badge" style="background: #e0e7ff; color: #4338ca;"><?= ucfirst($user['role']) ?></span></td>
                            <td>$<?= number_format($user['balance'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>