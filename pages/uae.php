<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../components/svg_icons.php';
$siteName = SITE_NAME; $siteUrl = SITE_URL;
?>
<!DOCTYPE html>
<html lang="en-AE">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best SMM Panel in UAE | Buy Followers, Likes & Views</title>
    <meta name="description" content="Top SMM panel in UAE. Buy Instagram followers, likes, TikTok views. Instant delivery, 30-day refill.">
    <meta name="geo.region" content="AE">
    <link rel="canonical" href="<?= $siteUrl ?>/uae">
    <link rel="stylesheet" href="<?= $siteUrl ?>/assets/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: var(--bg-primary); font-family: 'Inter', sans-serif; }
        .city-hero { background: linear-gradient(135deg, #00732f 0%, #00a95b 100%); padding: 5rem 0; text-align: center; color: white; }
        .city-hero-content { max-width: 800px; margin: 0 auto; }
        .city-badge { display: inline-block; background: rgba(255,255,255,0.2); padding: 8px 20px; border-radius: 50px; font-size: 0.9rem; margin-bottom: 1.5rem; }
        .city-hero h1 { font-size: 3rem; font-weight: 800; margin-bottom: 1rem; }
        .city-hero p { font-size: 1.25rem; opacity: 0.95; margin-bottom: 2rem; }
        .city-hero-btns { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .city-services, .city-testimonials { padding: 4rem 1.5rem; max-width: 1100px; margin: 0 auto; }
        .city-features { background: var(--bg-secondary); padding: 4rem 1.5rem; }
        .city-section-title { text-align: center; font-size: 2rem; color: var(--text-primary); margin-bottom: 3rem; }
        .services-city-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
        .city-service-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 1.75rem; }
        .city-service-card h3 { color: var(--text-primary); }
        .city-service-card .price { color: var(--primary); font-size: 1.5rem; font-weight: 700; }
        .city-service-card p { color: var(--text-secondary); }
        .features-city-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; max-width: 1100px; margin: 0 auto; }
        .city-feature-card { text-align: center; padding: 2rem; }
        .city-feature-icon { font-size: 2.5rem; }
        .city-cta { background: linear-gradient(135deg, #00732f 0%, #00a95b 100%); padding: 4rem 0; text-align: center; color: white; }
        .city-cta .btn { background: white; color: #00732f; padding: 14px 32px; font-size: 1.1rem; font-weight: 600; text-decoration: none; border-radius: 10px; }
    </style>
</head>
<body>
    <header class="header"><div class="container"><nav class="navbar"><a href="/" class="logo"><span class="logo-icon"><?= getIcon('logo') ?></span><span class="logo-text"><?= htmlspecialchars($siteName) ?></span></a><ul class="nav-links"><li><a href="/">Home</a></li><li><a href="/services">Services</a></li><li><a href="/global">Global</a></li><li><a href="/login" class="btn btn-outline">Login</a></li><li><a href="/register" class="btn btn-primary">Sign Up</a></li></ul></nav></div></header>
    <section class="city-hero"><div class="city-hero-content"><div class="city-badge">🇦🇪 Best SMM Panel in UAE</div><h1>Grow Your Social Media in UAE</h1><p>Join UAE creators and businesses who trust us. Dubai, Abu Dhabi & more.</p><div class="city-hero-btns"><a href="/register" class="btn btn-primary" style="background:white;color:#00732f;">Get Started Free</a><a href="/services" class="btn btn-secondary" style="border-color:white;color:white;">View Services</a></div></div></section>
    <section class="city-services"><h2 class="city-section-title">Our Services in UAE</h2><div class="services-city-grid"><div class="city-service-card"><h3>Instagram Followers</h3><div class="price">Starting at $2.99</div><p>Real followers for your profile</p></div><div class="city-service-card"><h3>Instagram Likes</h3><div class="price">Starting at $1.49</div><p>High-quality likes from active accounts</p></div><div class="city-service-card"><h3>YouTube Views</h3><div class="price">Starting at $3.99</div><p>Fast YouTube views delivery</p></div><div class="city-service-card"><h3>TikTok Followers</h3><div class="price">Starting at $2.49</div><p>Grow your TikTok audience</p></div></div></section>
    <section class="city-features"><h2 class="city-section-title">Why Choose Us in UAE?</h2><div class="features-city-grid"><div class="city-feature-card"><div class="city-feature-icon">⚡</div><h3>Instant Delivery</h3></div><div class="city-feature-card"><div class="city-feature-icon">🔄</div><h3>30-Day Refill</h3></div><div class="city-feature-card"><div class="city-feature-icon">🔒</div><h3>Secure Payment</h3></div><div class="city-feature-card"><div class="city-feature-icon">💬</div><h3>24/7 Support</h3></div></div></section>
    <section class="city-cta"><h2>Ready to Grow in UAE?</h2><p>Join thousands of satisfied customers.</p><a href="/register" class="btn">Create Free Account</a></section>
    <footer class="footer"><div class="container"><p>&copy; <?= date('Y') ?> <?= htmlspecialchars($siteName) ?>. All rights reserved.</p></div></footer>
    <script src="<?= $siteUrl ?>/assets/js/main.js"></script>
</body>
</html>