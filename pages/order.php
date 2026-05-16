<?php
/**
 * Place Order Page
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

session_start();

if (!isLoggedIn()) {
    header('Location: /login');
    exit;
}

$user = currentUser();
$services = Database::fetchAll("SELECT * FROM services WHERE status = 'active' ORDER BY platform, name");
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = (int)($_POST['service_id'] ?? 0);
    $link = $_POST['link'] ?? '';
    $quantity = (int)($_POST['quantity'] ?? 0);

    // Validation
    if (!$service_id) {
        $error = 'Please select a service';
    } elseif (empty($link)) {
        $error = 'Please enter the link';
    } elseif ($quantity < 1) {
        $error = 'Please enter quantity';
    } else {
        // Get service
        $service = Database::fetch("SELECT * FROM services WHERE id = ? AND status = 'active'", [$service_id]);
        if (!$service) {
            $error = 'Invalid service';
        } elseif ($quantity < $service['min_quantity'] || $quantity > $service['max_quantity']) {
            $error = "Quantity must be between {$service['min_quantity']} and {$service['max_quantity']}";
        } else {
            // Calculate price
            $price = calculatePrice($service['our_rate'], $quantity);
            if ($user['balance'] < $price) {
                $error = 'Insufficient balance. Please add funds.';
            } else {
                try {
                    // Create order
                    Database::query(
                        "INSERT INTO orders (user_id, service_id, smmwiz_service_id, service_name, link, requested_quantity, charge_smw, our_charge, profit, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')",
                        [
                            $user['id'],
                            $service_id,
                            $service['smmwiz_id'],
                            $service['name'],
                            $link,
                            $quantity,
                            $service['rate'] * $quantity,
                            $price,
                            $price - ($service['rate'] * $quantity)
                        ]
                    );

                    // Deduct balance
                    Database::query("UPDATE users SET balance = balance - ? WHERE id = ?", [$price, $user['id']]);

                    $success = 'Order placed successfully!';
                } catch (Exception $e) {
                    $error = 'Order failed: ' . $e->getMessage();
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order - SMM Panel</title>
    <link rel="stylesheet" href="https://smm-panel-5lqp.onrender.com/assets/css/index.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .header { background: #1e293b; color: white; padding: 20px; }
        .header .container { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; }
        .header h1 { font-size: 24px; }
        .nav { display: flex; gap: 20px; }
        .nav a { color: #94a3b8; text-decoration: none; }
        .nav a:hover { color: white; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .panel { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 20px; color: #1e293b; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; color: #374151; margin-bottom: 8px; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 16px; }
        .form-group small { color: #6b7280; }
        .btn { padding: 14px 24px; background: #6366f1; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; width: 100%; }
        .btn:hover { background: #4f46e5; }
        .error { background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px; }
        .success { background: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 20px; }
        .balance { background: #f0f9ff; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .balance strong { color: #0369a1; font-size: 24px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>📊 SMM Panel</h1>
            <nav class="nav">
                <a href="/dashboard">Dashboard</a>
                <a href="/services">Services</a>
                <a href="/order">New Order</a>
                <a href="/logout">Logout</a>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="panel">
            <h2>Place New Order</h2>

            <div class="balance">
                Your Balance: <strong>$<?= number_format($user['balance'], 2) ?></strong>
            </div>

            <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Select Service</label>
                    <select name="service_id" id="serviceSelect" required>
                        <option value="">-- Select Service --</option>
                        <?php foreach ($services as $s): ?>
                        <option value="<?= $s['id'] ?>" data-rate="<?= $s['our_rate'] ?>" data-min="<?= $s['min_quantity'] ?>" data-max="<?= $s['max_quantity'] ?>">
                            <?= htmlspecialchars($s['name']) ?> - $<?= number_format($s['our_rate'], 4) ?>/unit
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Link (Profile/Post URL)</label>
                    <input type="url" name="link" placeholder="https://instagram.com/yourprofile" required>
                    <small>Enter the URL of the profile, post, or video you want to promote</small>
                </div>

                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity" id="quantity" min="1" required>
                    <small id="qtyHelp">Enter quantity</small>
                </div>

                <div class="form-group">
                    <label>Total Price</label>
                    <div id="totalPrice" style="font-size: 24px; font-weight: 700; color: #6366f1;">$0.00</div>
                </div>

                <button type="submit" class="btn">Place Order</button>
            </form>
        </div>
    </div>

    <script>
        const select = document.getElementById('serviceSelect');
        const qtyInput = document.getElementById('quantity');
        const qtyHelp = document.getElementById('qtyHelp');
        const totalPrice = document.getElementById('totalPrice');

        select.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (option.value) {
                qtyHelp.textContent = `Min: ${option.dataset.min}, Max: ${option.dataset.max}`;
                qtyInput.min = option.dataset.min;
                qtyInput.max = option.dataset.max;
            }
        });

        qtyInput.addEventListener('input', function() {
            const option = select.options[select.selectedIndex];
            if (option.value && this.value) {
                const rate = parseFloat(option.dataset.rate);
                const total = rate * parseInt(this.value);
                totalPrice.textContent = '$' + total.toFixed(2);
            }
        });
    </script>
</body>
</html>