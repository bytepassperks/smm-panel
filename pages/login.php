<?php
/**
 * User Login Page
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

session_start();

if (isLoggedIn()) {
    header('Location: /dashboard');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $user = Database::fetch(
            "SELECT id, username, email, password, role, balance FROM users WHERE username = ? AND is_active = true",
            [$username]
        );

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['balance'] = $user['balance'];

            // Update last login
            Database::query("UPDATE users SET last_login = NOW() WHERE id = ?", [$user['id']]);

            header('Location: /dashboard');
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    } catch (Exception $e) {
        $error = 'Login failed: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMM Panel</title>
    <link rel="stylesheet" href="https://smm-panel-5lqp.onrender.com/assets/css/index.css">
    <style>
        body { background: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .login-container { max-width: 400px; margin: 100px auto; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 30px; color: #1e293b; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #374151; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 16px; box-sizing: border-box; }
        .btn-primary { width: 100%; padding: 14px; background: #6366f1; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .btn-primary:hover { background: #4f46e5; }
        .error { background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px; }
        .links { text-align: center; margin-top: 20px; color: #6b7280; }
        .links a { color: #6366f1; text-decoration: none; }
    </style>
</head>
<body>
    <div class="login-container">
        <form method="POST">
            <h2>🔑 User Login</h2>
            <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Login</button>
            <p class="links">Don't have an account? <a href="/register">Register</a></p>
        </form>
    </div>
</body>
</html>