<?php
/**
 * Admin - Manage Services (Prices, Markup, etc.)
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';

session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /admin/login');
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_service'])) {
        $id = (int)$_POST['id'];
        $our_rate = (float)$_POST['our_rate'];
        $markup_percentage = (float)$_POST['markup_percentage'];
        $status = $_POST['status'];
        $min_quantity = (int)$_POST['min_quantity'];
        $max_quantity = (int)$_POST['max_quantity'];

        Database::query(
            "UPDATE services SET our_rate = ?, markup_percentage = ?, status = ?, min_quantity = ?, max_quantity = ?, updated_at = NOW() WHERE id = ?",
            [$our_rate, $markup_percentage, $status, $min_quantity, $max_quantity, $id]
        );

        $message = "Service updated successfully!";
    }

    if (isset($_POST['sync_smmwiz'])) {
        // Get services from Smmwiz API
        require_once __DIR__ . '/../../includes/SmmwizClient.php';
        $client = new SmmwizClient(SMMWIZ_API_KEY, SMMWIZ_API_URL);
        $smmwizServices = $client->services();

        if (!empty($smmwizServices['data'])) {
            $synced = 0;
            foreach ($smmwizServices['data'] as $service) {
                // Check if exists
                $exists = Database::fetch("SELECT id FROM services WHERE smmwiz_id = ?", [$service['service']]);

                if ($exists) {
                    // Update
                    Database::query(
                        "UPDATE services SET name = ?, rate = ?, our_rate = rate * (1 + markup_percentage/100), min_quantity = ?, max_quantity = ?, updated_at = NOW() WHERE smmwiz_id = ?",
                        [$service['name'], $service['rate'], $service['min'], $service['max'], $service['service']]
                    );
                } else {
                    // Insert
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
            $message = "Synced $synced services from Smmwiz!";
        }
    }
}

$services = Database::fetchAll("SELECT * FROM services ORDER BY platform, category, name");
$platforms = array_unique(array_column($services, 'platform'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services - Admin</title>
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
        .btn-primary:hover { background: #4f46e5; }
        .btn-success { background: #10b981; color: white; }
        .btn-success:hover { background: #059669; }
        .filters { display: flex; gap: 10px; margin-bottom: 20px; }
        .filters select, .filters input { padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; }
        .message { background: #d1fae5; color: #065f46; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; }
        table { width: 100%; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { padding: 16px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f1f5f9; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; }
        tr:hover { background: #f8fafc; }
        .service-icon { font-size: 24px; }
        .platform-badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .platform-instagram { background: #fce7f3; color: #db2777; }
        .platform-facebook { background: #dbeafe; color: #2563eb; }
        .platform-tiktok { background: #f3e8ff; color: #9333ea; }
        .platform-youtube { background: #fee2e2; color: #dc2626; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .status-active { background: #d1fae5; color: #059669; }
        .status-inactive { background: #fee2e2; color: #dc2626; }
        .status-hidden { background: #f3f4f6; color: #6b7280; }
        .edit-form { display: none; background: #f8fafc; padding: 20px; border-radius: 8px; margin-top: 10px; }
        .edit-form.show { display: block; }
        .edit-form h4 { margin-bottom: 15px; color: #1e293b; }
        .form-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 15px; }
        .form-row input, .form-row select { padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; width: 100%; }
        .form-row label { display: block; font-size: 12px; color: #64748b; margin-bottom: 4px; }
        .form-actions { display: flex; gap: 10px; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .profit { color: #10b981; font-weight: 600; }
    </style>
    <script>
        function toggleEdit(id) {
            const form = document.getElementById('edit-' + id);
            form.classList.toggle('show');
        }
    </script>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <h1>⚙️ Admin Dashboard</h1>
            <nav class="admin-nav">
                <a href="/admin/dashboard">Dashboard</a>
                <a href="/admin/services" class="active">Services</a>
                <a href="/admin/users">Users</a>
                <a href="/admin/orders">Orders</a>
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
            <h2>Manage Services & Pricing</h2>
            <form method="POST" style="display:inline;">
                <button type="submit" name="sync_smmwiz" class="btn btn-success">🔄 Sync from Smmwiz</button>
            </form>
        </div>

        <div class="filters">
            <select onchange="filterPlatform(this.value)">
                <option value="">All Platforms</option>
                <?php foreach ($platforms as $p): ?>
                <option value="<?= $p ?>"><?= ucfirst($p) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Platform</th>
                    <th>Min/Max Qty</th>
                    <th>Cost (Smmwiz)</th>
                    <th>Your Price</th>
                    <th>Markup %</th>
                    <th>Profit/Unit</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $s): ?>
                <tr data-platform="<?= $s['platform'] ?>">
                    <td>
                        <span class="service-icon">
                            <?= ['instagram'=>'📸','facebook'=>'📘','tiktok'=>'🎵','youtube'=>'▶️'][$s['platform']] ?? '🌐' ?>
                        </span>
                        <?= htmlspecialchars($s['name']) ?>
                    </td>
                    <td><span class="platform-badge platform-<?= $s['platform'] ?>"><?= $s['platform'] ?></span></td>
                    <td><?= $s['min_quantity'] ?> - <?= number_format($s['max_quantity']) ?></td>
                    <td>$<?= number_format($s['rate'], 4) ?></td>
                    <td><strong>$<?= number_format($s['our_rate'], 4) ?></strong></td>
                    <td><?= $s['markup_percentage'] ?>%</td>
                    <td class="profit">+$<?= number_format($s['our_rate'] - $s['rate'], 4) ?></td>
                    <td><span class="status-badge status-<?= $s['status'] ?>"><?= $s['status'] ?></span></td>
                    <td>
                        <button onclick="toggleEdit(<?= $s['id'] ?>)" class="btn btn-primary btn-sm">✏️ Edit</button>
                    </td>
                </tr>
                <tr>
                    <td colspan="9">
                        <div id="edit-<?= $s['id'] ?>" class="edit-form">
                            <h4>Edit: <?= htmlspecialchars($s['name']) ?></h4>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                <input type="hidden" name="update_service" value="1">
                                <div class="form-row">
                                    <div>
                                        <label>Your Price ($)</label>
                                        <input type="number" step="0.0001" name="our_rate" value="<?= $s['our_rate'] ?>">
                                    </div>
                                    <div>
                                        <label>Markup (%)</label>
                                        <input type="number" step="0.01" name="markup_percentage" value="<?= $s['markup_percentage'] ?>">
                                    </div>
                                    <div>
                                        <label>Min Qty</label>
                                        <input type="number" name="min_quantity" value="<?= $s['min_quantity'] ?>">
                                    </div>
                                    <div>
                                        <label>Max Qty</label>
                                        <input type="number" name="max_quantity" value="<?= $s['max_quantity'] ?>">
                                    </div>
                                    <div>
                                        <label>Status</label>
                                        <select name="status">
                                            <option value="active" <?= $s['status']=='active'?'selected':'' ?>>Active</option>
                                            <option value="inactive" <?= $s['status']=='inactive'?'selected':'' ?>>Inactive</option>
                                            <option value="hidden" <?= $s['status']=='hidden'?'selected':'' ?>>Hidden</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success btn-sm">💾 Save Changes</button>
                                    <button type="button" onclick="toggleEdit(<?= $s['id'] ?>)" class="btn btn-sm">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function filterPlatform(platform) {
            const rows = document.querySelectorAll('tr[data-platform]');
            rows.forEach(row => {
                if (!platform || row.dataset.platform === platform) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>