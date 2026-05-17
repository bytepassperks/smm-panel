<?php
/**
 * City Landing Page - Hyderabad
 * GEO SEO optimized for Hyderabad, Telangana
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../components/svg_icons.php';

$siteName = SITE_NAME;
$siteUrl = SITE_URL;

$city = 'Hyderabad';
$pageTitle = "Best SMM Panel in {$city} | Buy Followers, Likes & Views";
$pageDescription = "Get instant social media followers, likes, and views in {$city}. Best SMM panel service in {$city} with 30-day refill guarantee.";

$services = [
    ['name' => 'Instagram Followers', 'starting' => '$2.99', 'desc' => 'Real followers for your profile'],
    ['name' => 'Instagram Likes', 'starting' => '$1.49', 'desc' => 'High-quality likes from active accounts'],
    ['name' => 'YouTube Views', 'starting' => '$3.99', 'desc' => 'Fast YouTube views delivery'],
    ['name' => 'TikTok Followers', 'starting' => '$2.49', 'desc' => 'Grow your TikTok audience'],
    ['name' => 'Facebook Page Likes', 'starting' => '$2.99', 'desc' => 'Authentic page likes'],
    ['name' => 'YouTube Subscribers', 'starting' => '$4.99', 'desc' => 'Real YouTube subscribers']
];

$features = [
    ['icon' => '⚡', 'title' => 'Instant Delivery', 'desc' => 'Most orders start within minutes'],
    ['icon' => '🔄', 'title' => '30-Day Refill', 'desc' => 'Free refill if numbers drop'],
    ['icon' => '🔒', 'title' => 'Secure Payment', 'desc' => 'UPI, Cards, Bank Transfer'],
    ['icon' => '💬', 'title' => '24/7 Support', 'desc' => 'Whatsapp & Email support']
];

$testimonials = [
    ['name' => 'Teja N.', 'role' => 'Tech Influencer', 'text' => 'Perfect for Hyderabad tech community! Great service with instant delivery.'],
    ['name' => 'Lakshmi P.', 'role' => 'Business Owner', 'text' => 'Helped our Hyderabad startup gain 10K followers in a month. Excellent rates!'],
    ['name' => 'Ravi K.', 'role' => 'Content Creator', 'text' => 'Best SMM panel for Hyderabad creators. Professional service with refill support.']
];
?>
<!DOCTYPE html>
<html lang="en-IN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="keywords" content="SMM panel Hyderabad, buy Instagram followers Hyderabad, best SMM panel Telangana">
    <meta name="robots" content="index, follow">
    <meta name="geo.region" content="IN-TG">
    <meta name="geo.placename" content="Hyderabad">
    <link rel="canonical" href="<?= $siteUrl ?>/hyderabad">
    <script type="application/ld+json">
    {"@context": "https://schema.org","@type": "LocalBusiness","name": "<?= htmlspecialchars($siteName) ?> - Hyderabad","description": "Best SMM panel in Hyderabad, Telangana.","url": "<?= $siteUrl ?>/hyderabad","areaServed": {"@type": "City", "name": "Hyderabad"},"address": {"@type": "PostalAddress", "addressRegion": "Telangana", "addressCountry": "IN"}}
    </script>
    <link rel="stylesheet" href="<?= $siteUrl ?>/assets/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: var(--bg-primary); font-family: 'Inter', sans-serif; }
        .city-hero { background: linear-gradient(135deg, #7f00ff 0%, #e100ff 100%); padding: 5rem 0; text-align: center; color: white; }
        .city-hero-content { max-width: 800px; margin: 0 auto; }
        .city-badge { display: inline-block; background: rgba(255,255,255,0.2); padding: 8px 20px; border-radius: 50px; font-size: 0.9rem; margin-bottom: 1.5rem; }
        .city-hero h1 { font-size: 3rem; font-weight: 800; margin-bottom: 1rem; }
        .city-hero p { font-size: 1.25rem; opacity: 0.95; margin-bottom: 2rem; }
        .city-hero-btns { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .city-services, .city-testimonials { padding: 4rem 1.5rem; max-width: 1100px; margin: 0 auto; }
        .city-features { background: var(--bg-secondary); padding: 4rem 1.5rem; }
        .city-section-title { text-align: center; font-size: 2rem; color: var(--text-primary); margin-bottom: 3rem; }
        .services-city-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
        .city-service-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 1.75rem; transition: all 0.3s; }
        .city-service-card:hover { border-color: var(--primary); transform: translateY(-5px); box-shadow: 0 15px 30px rgba(99,102,241,0.15); }
        .city-service-card h3 { color: var(--text-primary); font-size: 1.25rem; margin-bottom: 0.5rem; }
        .city-service-card .price { color: var(--primary); font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; }
        .city-service-card .price span { font-size: 0.9rem; font-weight: 400; }
        .city-service-card p { color: var(--text-secondary); font-size: 0.9rem; }
        .features-city-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; max-width: 1100px; margin: 0 auto; }
        .city-feature-card { text-align: center; padding: 2rem; }
        .city-feature-icon { font-size: 2.5rem; margin-bottom: 1rem; }
        .city-feature-card h3 { color: var(--text-primary); font-size: 1.1rem; margin-bottom: 0.5rem; }
        .city-feature-card p { color: var(--text-secondary); font-size: 0.9rem; }
        .city-testimonial { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 1.5rem; margin-bottom: 1rem; }
        .city-testimonial-text { color: var(--text-secondary); font-size: 0.95rem; line-height: 1.6; margin-bottom: 1rem; font-style: italic; }
        .city-testimonial-author strong { color: var(--text-primary); }
        .city-testimonial-author span { color: var(--text-muted); font-size: 0.85rem; }
        .city-cta { background: linear-gradient(135deg, #7f00ff 0%, #e100ff 100%); padding: 4rem 0; text-align: center; color: white; }
        .city-cta h2 { font-size: 2rem; margin-bottom: 1rem; }
        .city-cta p { font-size: 1.1rem; opacity: 0.9; margin-bottom: 2rem; }
        .city-cta .btn { background: white; color: #7f00ff; padding: 14px 32px; font-size: 1.1rem; font-weight: 600; text-decoration: none; border-radius: 10px; }
        body.dark-mode .city-service-card, body.dark-mode .city-testimonial { background: #1e293b; }
    </style>
</head>
<body>
    <header class="header"><div class="container"><nav class="navbar"><a href="/" class="logo"><span class="logo-icon"><?= getIcon('logo') ?></span><span class="logo-text"><?= htmlspecialchars($siteName) ?></span></a><ul class="nav-links"><li><a href="/">Home</a></li><li><a href="/services">Services</a></li><li><a href="/pricing">Pricing</a></li><li><a href="/blog">Blog</a></li><li><a href="/faq">FAQ</a></li><li><a href="/login" class="btn btn-outline">Login</a></li><li><a href="/register" class="btn btn-primary">Sign Up</a></li></ul><button class="hamburger"><span></span><span></span><span></span></button></nav></div></header>
    <section class="city-hero"><div class="city-hero-content"><div class="city-badge">📍 Best SMM Panel in Hyderabad</div><h1>Grow Your Social Media in Hyderabad</h1><p>Join thousands of tech enthusiasts and businesses in Hyderabad who trust us for their social media growth.</p><div class="city-hero-btns"><a href="/register" class="btn btn-primary" style="background: white; color: #7f00ff;">Get Started Free</a><a href="/services" class="btn btn-secondary" style="border-color: white; color: white;">View Services</a></div></div></section>
    <section class="city-services"><h2 class="city-section-title">Our Services in Hyderabad</h2><div class="services-city-grid"><?php foreach($services as $s): ?><div class="city-service-card"><h3><?= $s['name'] ?></h3><div class="price">Starting at <span><?= $s['starting'] ?></span></div><p><?= $s['desc'] ?></p></div><?php endforeach; ?></div></section>
    <section class="city-features"><h2 class="city-section-title">Why Choose Us in Hyderabad?</h2><div class="features-city-grid"><?php foreach($features as $f): ?><div class="city-feature-card"><div class="city-feature-icon"><?= $f['icon'] ?></div><h3><?= $f['title'] ?></h3><p><?= $f['desc'] ?></p></div><?php endforeach; ?></div></section>
    <section class="city-testimonials"><h2 class="city-section-title">What Hyderabad Customers Say</h2><?php foreach($testimonials as $t): ?><div class="city-testimonial"><p class="city-testimonial-text">"<?= $t['text'] ?>"</p><div class="city-testimonial-author"><strong><?= $t['name'] ?></strong><span>— <?= $t['role'] ?>, Hyderabad</span></div></div><?php endforeach; ?></section>
    <section class="city-cta"><div class="container"><h2>Ready to Grow in Hyderabad?</h2><p>Join thousands of satisfied customers across India.</p><a href="/register" class="btn">Create Free Account</a></div></section>
    <footer class="footer"><div class="container"><div class="footer-content"><div class="footer-section"><h4><?= htmlspecialchars($siteName) ?></h4><p>Best SMM panel in Hyderabad and all across India.</p></div><div class="footer-section"><h4>Quick Links</h4><ul><li><a href="/services">Services</a></li><li><a href="/pricing">Pricing</a></li><li><a href="/blog">Blog</a></li></ul></div></div><div class="footer-bottom"><p>&copy; <?= date('Y') ?> <?= htmlspecialchars($siteName) ?>. All rights reserved.</p></div></div></footer>
    <script src="<?= $siteUrl ?>/assets/js/main.js"></script>
</body>
</html>