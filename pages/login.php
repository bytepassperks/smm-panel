<?php
/**
 * User Login Page
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
    <title>Login - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: var(--bg-primary); font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; flex-direction: column; }
        .auth-page { flex: 1; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .auth-container { width: 100%; max-width: 420px; }
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
        .btn-primary { width: 100%; padding: 14px; background: var(--primary); color: white; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: background 0.2s, transform 0.1s; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-primary:active { transform: scale(0.98); }
        .error { background: #fef2f2; color: #dc2626; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .links { text-align: center; margin-top: 24px; color: var(--text-secondary); font-size: 14px; }
        .links a { color: var(--primary); text-decoration: none; font-weight: 500; }
        .links a:hover { text-decoration: underline; }
        .password-toggle { position: relative; }
        .password-toggle input { padding-right: 48px; }
        .toggle-password { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; color: var(--text-muted); }
        .toggle-password:hover { color: var(--text-secondary); }
        .divider { display: flex; align-items: center; margin: 24px 0; color: var(--text-muted); font-size: 13px; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border-color); }
        .divider span { padding: 0 12px; }
    </style>
</head>
<body>
    <div class="auth-page">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="logo-icon"><?= getIcon('logo') ?></div>
                    <h1>Welcome Back</h1>
                    <p>Sign in to your account</p>
                </div>
                <form method="POST">
                    <?php if ($error): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <div class="password-toggle">
                            <input type="password" name="password" id="password" placeholder="Enter your password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">Sign In</button>
                    <div class="divider"><span>or</span></div>
                    <p class="links">Don't have an account? <a href="/register">Create one</a></p>
                </form>
            </div>
        </div>
    </div>
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>