<?php
/**
 * Admin - Site Settings
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';

session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /admin/login');
    exit;
}

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_settings'])) {
        // Update default markup
        $default_markup = (float)$_POST['default_markup'];

        // Update all services with this markup
        Database::query("UPDATE services SET markup_percentage = ?", [$default_markup]);

        // Recalculate prices
        $services = Database::fetchAll("SELECT id, rate FROM services");
        foreach ($services as $s) {
            $new_our_rate = $s['rate'] * (1 + $default_markup / 100);
            Database::query("UPDATE services SET our_rate = ? WHERE id = ?", [$new_our_rate, $s['id']]);
        }

        $message = "Default markup updated to " . $default_markup . "% and all prices recalculated!";
    }

    if (isset($_POST['sync_all'])) {
        require_once __DIR__ . '/../../includes/SmmwizClient.php';
        $client = new SmmwizClient(SMMWIZ_API_KEY, SMMWIZ_API_URL);

        // Sync services
        $smmwizServices = $client->services();
        $synced = 0;
        if (!empty($smmwizServices['data'])) {
            foreach ($smmwizServices['data'] as $service) {
                $exists = Database::fetch("SELECT id FROM services WHERE smmwiz_id = ?", [$service['service']]);
                if ($exists) {
                    Database::query(
                        "UPDATE services SET name = ?, rate = ?, min_quantity = ?, max_quantity = ?, updated_at = NOW() WHERE smmwiz_id = ?",
                        [$service['name'], $service['rate'], $service['min'], $service['max'], $service['service']]
                    );
                } else {
                    Database::query(
                        "INSERT INTO services (smmwiz_id, platform, name, type, category, min_quantity, max_quantity, rate, our_rate, markup_percentage, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                        [
                            $service['service'],
                            strtolower($service['category'] ?? 'other'),
                            $service['name'],
                            $service['type'] ?? 'other',
                            $service['category'] ?? 'Other',
                            $service['min'],
                            $service['max'],
                            $service['rate'],
                            $service['rate'] * 1.2,
                            DEFAULT_MARKUP,
                            'active'
                        ]
                    );
                }
                $synced++;
            }
        }

        // Get Smmwiz balance
        $balance = $client->balance();

        $message = "Synced $synced services from Smmwiz! API Balance: $" . number_format($balance['balance'] ?? 0, 2);
    }
}

// Get current settings
$serviceCount = Database::count('services', "status = 'active'");
$userCount = Database::count('users');
$orderCount = Database::count('orders');
$totalRevenue = Database::fetch("SELECT SUM(charge_smw) as total FROM orders")['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin</title>
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
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .message { background: #d1fae5; color: #065f46; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; }
        .panel { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .panel h2 { font-size: 20px; margin-bottom: 20px; color: #1e293b; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; color: #374151; margin-bottom: 8px; }
        .form-group input, .form-group select { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 16px; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #6366f1; }
        .form-group small { color: #6b7280; font-size: 14px; }
        .btn { padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; font-size: 14px; }
        .btn-primary { background: #6366f1; color: white; }
        .btn-primary:hover { background: #4f46e5; }
        .btn-success { background: #10b981; color: white; }
        .btn-success:hover { background: #059669; }
        .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px; }
        .stat-box { background: #f8fafc; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-box .label { color: #64748b; font-size: 14px; }
        .stat-box .value { font-size: 24px; font-weight: 700; color: #1e293b; margin-top: 5px; }
        .info-box { background: #f0f9ff; border: 1px solid #bae6fd; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .info-box h4 { color: #0369a1; margin-bottom: 8px; }
        .info-box p { color: #075985; font-size: 14px; }
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
                <a href="/admin/orders">Orders</a>
                <a href="/admin/settings" class="active">Settings</a>
                <a href="/admin/logout" class="logout">Logout</a>
            </nav>
        </div>
    </div>

    <div class="container">
        <?php if (isset($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="stats-row">
            <div class="stat-box">
                <div class="label">Active Services</div>
                <div class="value"><?= $serviceCount ?></div>
            </div>
            <div class="stat-box">
                <div class="label">Total Users</div>
                <div class="value"><?= $userCount ?></div>
            </div>
            <div class="stat-box">
                <div class="label">Total Orders</div>
                <div class="value"><?= $orderCount ?></div>
            </div>
            <div class="stat-box">
                <div class="label">Total Revenue</div>
                <div class="value">$<?= number_format($totalRevenue, 2) ?></div>
            </div>
        </div>

        <div class="panel">
            <h2>💰 Default Markup Settings</h2>
            <form method="POST">
                <input type="hidden" name="update_settings" value="1">
                <div class="form-group">
                    <label>Default Markup Percentage (%)</label>
                    <input type="number" step="0.01" name="default_markup" value="<?= DEFAULT_MARKUP ?>" required>
                    <small>This will update the selling price for all services. Formula: Your Price = Smmwiz Cost × (1 + Markup%)</small>
                </div>
                <button type="submit" class="btn btn-primary">Update All Prices</button>
            </form>
        </div>

        <div class="panel">
            <h2>🔄 Sync from Smmwiz</h2>
            <div class="info-box">
                <h4>What does this do?</h4>
                <p>Clicking sync will fetch the latest services, prices, and categories from the Smmwiz API and update your database. Existing services will be updated, new services will be added.</p>
            </div>
            <form method="POST">
                <input type="hidden" name="sync_all" value="1">
                <button type="submit" class="btn btn-success">🔄 Sync Services & Prices</button>
            </form>
        </div>

        <div class="panel">
            <h2>🔗 API Configuration</h2>
            <div class="form-group">
                <label>Smmwiz API URL</label>
                <input type="text" value="<?= SMMWIZ_API_URL ?>" disabled>
            </div>
            <div class="form-group">
                <label>API Key</label>
                <input type="password" value="<?= substr(SMMWIZ_API_KEY, 0, 10) ?>..." disabled>
            </div>
        </div>

        <div class="panel">
            <h2>🔐 Change Admin Password</h2>
            <form method="POST">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" placeholder="Enter new password">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Confirm new password">
                </div>
                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>
    </div>
</body>
</html>