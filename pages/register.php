<?php
/**
 * User Registration Page
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../components/svg_icons.php';

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
    <title>Register - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: var(--bg-primary); font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; flex-direction: column; }
        .auth-page { flex: 1; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .auth-container { width: 100%; max-width: 460px; }
        .auth-card { background: var(--bg-card); border-radius: 16px; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .auth-header { text-align: center; margin-bottom: 32px; }
        .auth-header .logo-icon { width: 56px; height: 56px; margin-bottom: 16px; display: inline-block; }
        .auth-header h1 { font-size: 24px; color: var(--text-primary); margin: 0 0 8px; }
        .auth-header p { color: var(--text-secondary); margin: 0; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--text-primary); }
        .form-group input { width: 100%; padding: 14px 16px; border: 1px solid var(--border-color); border-radius: 10px; font-size: 15px; background: var(--bg-input); color: var(--text-primary); transition: border-color 0.2s, box-shadow 0.2s; box-sizing: border-box; }
        .form-group input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .form-group input::placeholder { color: var(--text-muted); }
        .strength-bar { height: 4px; background: var(--border-color); border-radius: 2px; margin-top: 8px; overflow: hidden; }
        .strength-bar-fill { height: 100%; width: 0; transition: width 0.3s, background 0.3s; border-radius: 2px; }
        .btn-primary { width: 100%; padding: 14px; background: var(--primary); color: white; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: background 0.2s, transform 0.1s; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-primary:active { transform: scale(0.98); }
        .error { background: #fef2f2; color: #dc2626; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .success { background: #ecfdf5; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .links { text-align: center; margin-top: 24px; color: var(--text-secondary); font-size: 14px; }
        .links a { color: var(--primary); text-decoration: none; font-weight: 500; }
        .links a:hover { text-decoration: underline; }
        .password-toggle { position: relative; }
        .password-toggle input { padding-right: 48px; }
        .toggle-password { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; color: var(--text-muted); }
        .toggle-password:hover { color: var(--text-secondary); }
        .terms { font-size: 13px; color: var(--text-secondary); margin-top: 16px; text-align: center; }
        .terms a { color: var(--primary); }
    </style>
</head>
<body>
    <div class="auth-page">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="logo-icon"><?= getIcon('logo') ?></div>
                    <h1>Create Account</h1>
                    <p>Join thousands of satisfied customers</p>
                </div>
                <form method="POST">
                    <?php if ($error): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                    <div class="success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Choose a username" required minlength="3">
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <div class="password-toggle">
                            <input type="password" name="password" id="password" placeholder="Create a password" required minlength="6">
                            <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                        <div class="strength-bar"><div class="strength-bar-fill" id="strengthBar"></div></div>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <div class="password-toggle">
                            <input type="password" name="password_confirm" id="password_confirm" placeholder="Confirm your password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirm')">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">Create Account</button>
                    <p class="terms">By registering, you agree to our <a href="/terms">Terms of Service</a> and <a href="/privacy">Privacy Policy</a></p>
                    <p class="links">Already have an account? <a href="/login">Sign in</a></p>
                </form>
            </div>
        </div>
    </div>
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        document.getElementById('password').addEventListener('input', function() {
            const val = this.value;
            let strength = 0;
            if (val.length >= 6) strength++;
            if (val.length >= 8) strength++;
            if (val.match(/[a-z]/) && val.match(/[A-Z]/)) strength++;
            if (val.match(/\d/)) strength++;
            if (val.match(/[^a-zA-Z\d]/)) strength++;

            const colors = ['#ef4444', '#f59e0b', '#10b981', '#6366f1', '#22c55e'];
            const bar = document.getElementById('strengthBar');
            bar.style.width = (strength * 20) + '%';
            bar.style.backgroundColor = colors[Math.min(strength - 1, 4)] || colors[0];
        });
    </script>
</body>
</html>