<?php
/**
 * User Dashboard
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

session_start();

// Check if logged in
if (!isLoggedIn()) {
    header('Location: /login');
    exit;
}

$user = currentUser();

// Get user's orders
$orders = Database::fetchAll(
    "SELECT o.*, s.name as service_name
     FROM orders o
     LEFT JOIN services s ON o.service_id = s.id
     WHERE o.user_id = ?
     ORDER BY o.created_at DESC
     LIMIT 20",
    [$user['id']]
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SMM Panel</title>
    <link rel="stylesheet" href="https://smm-panel-5lqp.onrender.com/assets/css/index.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .header { background: #1e293b; color: white; padding: 20px; }
        .header .container { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; }
        .header h1 { font-size: 24px; }
        .nav { display: flex; gap: 20px; }
        .nav a { color: #94a3b8; text-decoration: none; }
        .nav a:hover, .nav a.active { color: white; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .welcome { background: white; padding: 30px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .welcome h2 { color: #1e293b; margin-bottom: 10px; }
        .balance-card { background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; padding: 30px; border-radius: 12px; margin-bottom: 20px; }
        .balance-card h3 { opacity: 0.9; font-size: 14px; }
        .balance-card .amount { font-size: 36px; font-weight: 700; margin-top: 10px; }
        .btn { padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #6366f1; color: white; }
        .btn-outline { border: 1px solid #6366f1; color: #6366f1; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .panel { background: white; padding: 24px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .panel h3 { color: #1e293b; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { font-weight: 600; color: #64748b; font-size: 12px; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-completed { background: #d1fae5; color: #059669; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>📊 SMM Panel</h1>
            <nav class="nav">
                <a href="/dashboard" class="active">Dashboard</a>
                <a href="/services">Services</a>
                <a href="/order">New Order</a>
                <a href="/logout">Logout (<?= htmlspecialchars($user['username']) ?>)</a>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="welcome">
            <h2>Welcome back, <?= htmlspecialchars($user['username']) ?>!</h2>
            <p>Your account is active. You can place orders and track their progress here.</p>
        </div>

        <div class="balance-card">
            <h3>Your Balance</h3>
            <div class="amount">$<?= number_format($user['balance'], 2) ?></div>
            <a href="#" class="btn btn-outline" style="color: white; border-color: white; margin-top: 15px;">Add Funds</a>
        </div>

        <div class="grid">
            <div class="panel">
                <h3>Quick Actions</h3>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="/services" class="btn btn-primary">Browse Services</a>
                    <a href="/order" class="btn btn-outline">Place New Order</a>
                </div>
            </div>
            <div class="panel">
                <h3>Account Info</h3>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Role:</strong> <?= ucfirst($user['role']) ?></p>
                <p><strong>Member since:</strong> Just now</p>
            </div>
        </div>

        <div class="panel" style="margin-top: 20px;">
            <h3>Recent Orders</h3>
            <?php if (empty($orders)): ?>
            <p style="color: #94a3b8; text-align: center; padding: 40px;">No orders yet. <a href="/services">Browse services</a> to place your first order!</p>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Service</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                    <tr>
                        <td>#<?= $o['id'] ?></td>
                        <td><?= htmlspecialchars($o['service_name'] ?? 'N/A') ?></td>
                        <td><?= number_format($o['requested_quantity']) ?></td>
                        <td>$<?= number_format($o['charge_smw'], 2) ?></td>
                        <td><span class="status-badge status-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
                        <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>