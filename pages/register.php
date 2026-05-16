<?php
/**
 * User Registration Page
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
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validation
    if (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password !== $password_confirm) {
        $error = 'Passwords do not match';
    } else {
        try {
            // Check if username exists
            $exists = Database::fetch("SELECT id FROM users WHERE username = ? OR email = ?", [$username, $email]);
            if ($exists) {
                $error = 'Username or email already exists';
            } else {
                // Create user
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                Database::query(
                    "INSERT INTO users (email, username, password, role, balance, is_active) VALUES (?, ?, ?, 'customer', 0.00, true)",
                    [$email, $username, $hashed_password]
                );

                $success = 'Registration successful! You can now login.';
            }
        } catch (Exception $e) {
            $error = 'Registration failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SMM Panel</title>
    <link rel="stylesheet" href="https://smm-panel-5lqp.onrender.com/assets/css/index.css">
    <style>
        body { background: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .register-container { max-width: 450px; margin: 60px auto; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 30px; color: #1e293b; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #374151; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 16px; box-sizing: border-box; }
        .btn-primary { width: 100%; padding: 14px; background: #6366f1; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .btn-primary:hover { background: #4f46e5; }
        .error { background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px; }
        .success { background: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 20px; }
        .links { text-align: center; margin-top: 20px; color: #6b7280; }
        .links a { color: #6366f1; text-decoration: none; }
    </style>
</head>
<body>
    <div class="register-container">
        <form method="POST">
            <h2>📝 Create Account</h2>
            <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required minlength="3">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required minlength="6">
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirm" required>
            </div>
            <button type="submit" class="btn-primary">Register</button>
            <p class="links">Already have an account? <a href="/login">Login</a></p>
        </form>
    </div>
</body>
</html>