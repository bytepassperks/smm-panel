<?php
/**
 * Global Landing Page - International SEO
 * Serves as the main international landing page
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../components/svg_icons.php';

$siteName = SITE_NAME;
$siteUrl = SITE_URL;

// SEO for global
$pageTitle = "Best SMM Panel Worldwide | Buy Followers, Likes & Views";
$pageDescription = "Premium SMM panel serving customers globally. Buy Instagram followers, likes, views, TikTok followers, YouTube views and more. Instant delivery, 30-day refill guarantee.";

$countries = [
    ['name' => 'India', 'flag' => '🇮🇳', 'url' => '/delhi', 'desc' => 'SMM panel in Delhi, Mumbai, Bangalore, Chennai, Kolkata, Hyderabad'],
    ['name' => 'USA', 'flag' => '🇺🇸', 'url' => '/usa', 'desc' => 'Best SMM panel in USA - New York, LA, Chicago, Houston'],
    ['name' => 'UK', 'flag' => '🇬🇧', 'url' => '/uk', 'desc' => 'SMM panel UK - London, Manchester, Birmingham'],
    ['name' => 'UAE', 'flag' => '🇦🇪', 'url' => '/uae', 'desc' => 'SMM panel Dubai, Abu Dhabi, Sharjah'],
    ['name' => 'Pakistan', 'flag' => '🇵🇰', 'url' => '/pakistan', 'desc' => 'SMM panel Pakistan - Karachi, Lahore, Islamabad'],
    ['name' => 'Bangladesh', 'flag' => '🇧🇩', 'url' => '/bangladesh', 'desc' => 'SMM panel Bangladesh - Dhaka, Chittagong']
];

$services = [
    ['name' => 'Instagram', 'icon' => '📸', 'services' => ['Followers', 'Likes', 'Views', 'Comments']],
    ['name' => 'Facebook', 'icon' => '📘', 'services' => ['Page Likes', 'Followers', 'Post Likes', 'Page Reviews']],
    ['name' => 'TikTok', 'icon' => '🎵', 'services' => ['Followers', 'Likes', 'Views', 'Shares']],
    ['name' => 'YouTube', 'icon' => '▶️', 'services' => ['Subscribers', 'Views', 'Likes', 'Comments']]
];

$features = [
    ['icon' => '🌍', 'title' => 'Global Coverage', 'desc' => 'Serving 150+ countries worldwide'],
    ['icon' => '⚡', 'title' => 'Instant Delivery', 'desc' => 'Most orders start within minutes'],
    ['icon' => '🔄', 'title' => '30-Day Refill', 'desc' => 'Free refill guarantee on all services'],
    ['icon' => '🔒', 'title' => 'Secure Payments', 'desc' => 'Multiple payment options available'],
    ['icon' => '💬', 'title' => '24/7 Support', 'desc' => 'Round the clock customer support'],
    ['icon' => '💰', 'title' => 'Wholesale Prices', 'desc' => 'Best rates in the industry']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="keywords" content="SMM panel, buy followers, buy likes, buy views, social media marketing, international SMM panel, global SMM services">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= $siteUrl ?>/global">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $siteUrl ?>/global">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
    <script type="application/ld+json">
    {"@context": "https://schema.org","@type": "Organization","name": "<?= htmlspecialchars($siteName) ?>","description": "<?= htmlspecialchars($pageDescription) ?>","url": "<?= $siteUrl ?>","areaServed": "Worldwide","currenciesAccepted": "USD"}
    </script>
    <link rel="stylesheet" href="<?= $siteUrl ?>/assets/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: var(--bg-primary); font-family: 'Inter', sans-serif; }
        .global-hero { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); padding: 5rem 0; text-align: center; color: white; }
        .global-hero-content { max-width: 900px; margin: 0 auto; }
        .global-hero h1 { font-size: 3rem; font-weight: 800; margin-bottom: 1rem; }
        .global-hero p { font-size: 1.25rem; opacity: 0.9; margin-bottom: 2rem; }
        .global-stats { display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-top: 2rem; }
        .global-stat { text-align: center; }
        .global-stat-number { font-size: 2rem; font-weight: 800; color: #4ade80; }
        .global-stat-label { font-size: 0.9rem; opacity: 0.8; }
        .countries-section { padding: 4rem 1.5rem; max-width: 1200px; margin: 0 auto; }
        .section-title { text-align: center; font-size: 2rem; color: var(--text-primary); margin-bottom: 3rem; }
        .countries-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; }
        .country-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 1.75rem; text-decoration: none; color: inherit; transition: all 0.3s; }
        .country-card:hover { border-color: var(--primary); transform: translateY(-5px); box-shadow: 0 15px 30px rgba(99,102,241,0.15); }
        .country-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
        .country-flag { font-size: 2.5rem; }
        .country-name { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); }
        .country-desc { color: var(--text-secondary); font-size: 0.9rem; }
        .services-section { background: var(--bg-secondary); padding: 4rem 1.5rem; }
        .services-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; max-width: 1200px; margin: 0 auto; }
        .service-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 2rem; text-align: center; transition: all 0.3s; }
        .service-card:hover { border-color: var(--primary); transform: translateY(-5px); }
        .service-icon { font-size: 3rem; margin-bottom: 1rem; }
        .service-card h3 { color: var(--text-primary); margin-bottom: 1rem; }
        .service-list { list-style: none; padding: 0; }
        .service-list li { color: var(--text-secondary); padding: 0.5rem 0; font-size: 0.9rem; }
        .features-section { padding: 4rem 1.5rem; max-width: 1200px; margin: 0 auto; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }
        .feature-card { display: flex; align-items: flex-start; gap: 1rem; padding: 1.5rem; }
        .feature-icon { font-size: 2rem; }
        .feature-content h3 { color: var(--text-primary); font-size: 1.1rem; margin-bottom: 0.5rem; }
        .feature-content p { color: var(--text-secondary); font-size: 0.9rem; }
        .global-cta { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 4rem 0; text-align: center; color: white; }
        .global-cta h2 { font-size: 2rem; margin-bottom: 1rem; }
        .global-cta p { font-size: 1.1rem; opacity: 0.9; margin-bottom: 2rem; }
        .global-cta .btn { background: white; color: #667eea; padding: 14px 32px; font-size: 1.1rem; font-weight: 600; text-decoration: none; border-radius: 10px; }
        body.dark-mode .country-card, body.dark-mode .service-card { background: #1e293b; }
    </style>
</head>
<body>
    <header class="header"><div class="container"><nav class="navbar"><a href="/" class="logo"><span class="logo-icon"><?= getIcon('logo') ?></span><span class="logo-text"><?= htmlspecialchars($siteName) ?></span></a><ul class="nav-links"><li><a href="/">Home</a></li><li><a href="/services">Services</a></li><li><a href="/pricing">Pricing</a></li><li><a href="/blog">Blog</a></li><li><a href="/faq">FAQ</a></li><li><a href="/login" class="btn btn-outline">Login</a></li><li><a href="/register" class="btn btn-primary">Sign Up</a></li></ul><button class="hamburger"><span></span><span></span><span></span></button></nav></div></header>
    <section class="global-hero"><div class="global-hero-content"><h1>🌎 World's #1 SMM Panel</h1><p>Serving 150+ countries with instant social media growth. Trusted by 50,000+ customers worldwide.</p><div class="global-stats"><div class="global-stat"><div class="global-stat-number">50K+</div><div class="global-stat-label">Customers</div></div><div class="global-stat"><div class="global-stat-number">2M+</div><div class="global-stat-label">Orders</div></div><div class="global-stat"><div class="global-stat-number">150+</div><div class="global-stat-label">Countries</div></div><div class="global-stat"><div class="global-stat-number">24/7</div><div class="global-stat-label">Support</div></div></div></div></section>
    <section class="countries-section"><h2 class="section-title">🌍 Choose Your Country</h2><div class="countries-grid"><?php foreach($countries as $c): ?><a href="<?= $c['url'] ?>" class="country-card"><div class="country-header"><span class="country-flag"><?= $c['flag'] ?></span><span class="country-name"><?= $c['name'] ?></span></div><p class="country-desc"><?= $c['desc'] ?></p></a><?php endforeach; ?></div></section>
    <section class="services-section"><h2 class="section-title">📱 Our Services</h2><div class="services-grid"><?php foreach($services as $s): ?><div class="service-card"><div class="service-icon"><?= $s['icon'] ?></div><h3><?= $s['name'] ?></h3><ul class="service-list"><?php foreach($s['services'] as $srv): ?><li><?= $srv ?></li><?php endforeach; ?></ul></div><?php endforeach; ?></div></section>
    <section class="features-section"><h2 class="section-title">✨ Why Choose Us</h2><div class="features-grid"><?php foreach($features as $f): ?><div class="feature-card"><div class="feature-icon"><?= $f['icon'] ?></div><div class="feature-content"><h3><?= $f['title'] ?></h3><p><?= $f['desc'] ?></p></div></div><?php endforeach; ?></div></section>
    <section class="global-cta"><div class="container"><h2>Start Growing Globally Today!</h2><p>Join thousands of satisfied customers from around the world.</p><a href="/register" class="btn">Create Free Account</a></div></section>
    <footer class="footer"><div class="container"><div class="footer-content"><div class="footer-section"><h4><?= htmlspecialchars($siteName) ?></h4><p>World's leading SMM panel serving 150+ countries.</p></div><div class="footer-section"><h4>Quick Links</h4><ul><li><a href="/services">Services</a></li><li><a href="/pricing">Pricing</a></li><li><a href="/blog">Blog</a></li></ul></div></div><div class="footer-bottom"><p>&copy; <?= date('Y') ?> <?= htmlspecialchars($siteName) ?>. All rights reserved.</p></div></div></footer>
    <script src="<?= $siteUrl ?>/assets/js/main.js"></script>
</body>
</html>