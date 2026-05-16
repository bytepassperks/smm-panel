<?php
/**
 * Admin - Manage Users
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
    if (isset($_POST['update_user'])) {
        $id = (int)$_POST['id'];
        $role = $_POST['role'];
        $balance = (float)$_POST['balance'];
        $is_active = isset($_POST['is_active']) ? 'true' : 'false';

        Database::query(
            "UPDATE users SET role = ?, balance = ?, is_active = ?, updated_at = NOW() WHERE id = ?",
            [$role, $balance, $is_active, $id]
        );
        $message = "User updated!";
    }

    if (isset($_POST['add_funds'])) {
        $id = (int)$_POST['id'];
        $amount = (float)$_POST['amount'];
        Database::query("UPDATE users SET balance = balance + ? WHERE id = ?", [$amount, $id]);
        $message = "Added $" . number_format($amount, 2) . " to user balance!";
    }
}

$users = Database::fetchAll("SELECT * FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
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
        .message { background: #d1fae5; color: #065f46; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; }
        table { width: 100%; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { padding: 16px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f1f5f9; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; }
        tr:hover { background: #f8fafc; }
        .role-badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .role-admin { background: #fef3c7; color: #d97706; }
        .role-reseller { background: #dbeafe; color: #2563eb; }
        .role-customer { background: #f3f4f6; color: #6b7280; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .status-active { background: #d1fae5; color: #059669; }
        .status-inactive { background: #fee2e2; color: #dc2626; }
        .btn { padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; }
        .btn-primary { background: #6366f1; color: white; }
        .btn-success { background: #10b981; color: white; }
        .edit-form { display: none; background: #f8fafc; padding: 20px; border-radius: 8px; margin-top: 10px; }
        .edit-form.show { display: block; }
        .form-row { display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end; }
        .form-row > div { min-width: 120px; }
        .form-row label { display: block; font-size: 12px; color: #64748b; margin-bottom: 4px; }
        .form-row input, .form-row select { padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; }
        .balance-positive { color: #10b981; font-weight: 600; }
    </style>
    <script>
        function toggleEdit(id) { document.getElementById('edit-' + id).classList.toggle('show'); }
        function toggleFunds(id) { document.getElementById('funds-' + id).classList.toggle('show'); }
    </script>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <h1>⚙️ Admin Dashboard</h1>
            <nav class="admin-nav">
                <a href="/admin/dashboard">Dashboard</a>
                <a href="/admin/services">Services</a>
                <a href="/admin/users" class="active">Users</a>
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

        <h2>Manage Users</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td>#<?= $u['id'] ?></td>
                    <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><span class="role-badge role-<?= $u['role'] ?>"><?= $u['role'] ?></span></td>
                    <td class="balance-positive">$<?= number_format($u['balance'], 2) ?></td>
                    <td><span class="status-badge status-<?= $u['is_active'] ? 'active' : 'inactive' ?>"><?= $u['is_active'] ? 'Active' : 'Inactive' ?></span></td>
                    <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                    <td><?= $u['last_login'] ? date('d M Y, h:i A', strtotime($u['last_login'])) : 'Never' ?></td>
                    <td>
                        <button onclick="toggleEdit(<?= $u['id'] ?>)" class="btn btn-primary btn-sm">✏️</button>
                        <button onclick="toggleFunds(<?= $u['id'] ?>)" class="btn btn-success btn-sm">💰</button>
                    </td>
                </tr>
                <tr>
                    <td colspan="9">
                        <div id="edit-<?= $u['id'] ?>" class="edit-form">
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <input type="hidden" name="update_user" value="1">
                                <div class="form-row">
                                    <div>
                                        <label>Role</label>
                                        <select name="role">
                                            <option value="customer" <?= $u['role']=='customer'?'selected':'' ?>>Customer</option>
                                            <option value="reseller" <?= $u['role']=='reseller'?'selected':'' ?>>Reseller</option>
                                            <option value="admin" <?= $u['role']=='admin'?'selected':'' ?>>Admin</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Balance ($)</label>
                                        <input type="number" step="0.01" name="balance" value="<?= $u['balance'] ?>">
                                    </div>
                                    <div>
                                        <label>Active</label>
                                        <input type="checkbox" name="is_active" <?= $u['is_active']?'checked':'' ?>>
                                    </div>
                                    <button type="submit" class="btn btn-success">💾 Save</button>
                                </div>
                            </form>
                        </div>
                        <div id="funds-<?= $u['id'] ?>" class="edit-form">
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <input type="hidden" name="add_funds" value="1">
                                <div class="form-row">
                                    <div>
                                        <label>Add Amount ($)</label>
                                        <input type="number" step="0.01" name="amount" placeholder="Enter amount">
                                    </div>
                                    <button type="submit" class="btn btn-success">➕ Add Funds</button>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>