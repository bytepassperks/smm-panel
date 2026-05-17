<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../components/svg_icons.php';
$siteName = SITE_NAME; $siteUrl = SITE_URL;
$country = 'UK';
$pageTitle = "Best SMM Panel in {$country} | Buy Followers, Likes & Views";
$pageDescription = "Top SMM panel in UK. Buy Instagram followers, likes, TikTok views, YouTube subscribers. Instant delivery, 30-day refill.";
?>
<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="keywords" content="SMM panel UK, buy followers UK, best SMM panel Britain">
    <meta name="geo.region" content="GB">
    <link rel="canonical" href="<?= $siteUrl ?>/uk">
    <script type="application/ld+json">{"@context":"https://schema.org","@type":"LocalBusiness","name":"<?= htmlspecialchars($siteName) ?> - UK","url":"<?= $siteUrl ?>/uk","areaServed":{"@type":"Country","name":"United Kingdom"}}</script>
    <link rel="stylesheet" href="<?= $siteUrl ?>/assets/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: var(--bg-primary); font-family: 'Inter', sans-serif; }
        .city-hero { background: linear-gradient(135deg, #012169 0%, #003399 100%); padding: 5rem 0; text-align: center; color: white; }
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
        .city-service-card:hover { border-color: var(--primary); transform: translateY(-5px); }
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
        .city-testimonial-text { color: var(--text-secondary); font-size: 0.95rem; margin-bottom: 1rem; font-style: italic; }
        .city-testimonial-author strong { color: var(--text-primary); }
        .city-testimonial-author span { color: var(--text-muted); font-size: 0.85rem; }
        .city-cta { background: linear-gradient(135deg, #012169 0%, #003399 100%); padding: 4rem 0; text-align: center; color: white; }
        .city-cta h2 { font-size: 2rem; margin-bottom: 1rem; }
        .city-cta p { font-size: 1.1rem; opacity: 0.9; margin-bottom: 2rem; }
        .city-cta .btn { background: white; color: #012169; padding: 14px 32px; font-size: 1.1rem; font-weight: 600; text-decoration: none; border-radius: 10px; }
        .cities-served { padding: 2rem 1.5rem; max-width: 800px; margin: 0 auto; text-align: center; }
        .cities-list { display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; }
        .city-tag { background: var(--bg-card); border: 1px solid var(--border-color); padding: 8px 16px; border-radius: 20px; color: var(--text-secondary); font-size: 0.9rem; }
        body.dark-mode .city-service-card, body.dark-mode .city-testimonial { background: #1e293b; }
    </style>
</head>
<body>
    <header class="header"><div class="container"><nav class="navbar"><a href="/" class="logo"><span class="logo-icon"><?= getIcon('logo') ?></span><span class="logo-text"><?= htmlspecialchars($siteName) ?></span></a><ul class="nav-links"><li><a href="/">Home</a></li><li><a href="/services">Services</a></li><li><a href="/global">Global</a></li><li><a href="/faq">FAQ</a></li><li><a href="/login" class="btn btn-outline">Login</a></li><li><a href="/register" class="btn btn-primary">Sign Up</a></li></ul><button class="hamburger"><span></span><span></span><span></span></button></nav></div></header>
    <section class="city-hero"><div class="city-hero-content"><div class="city-badge">🇬🇧 Best SMM Panel in UK</div><h1>Grow Your Social Media in UK</h1><p>Join thousands of UK creators and businesses who trust us for their social media growth.</p><div class="city-hero-btns"><a href="/register" class="btn btn-primary" style="background: white; color: #012169;">Get Started Free</a><a href="/services" class="btn btn-secondary" style="border-color: white; color: white;">View Services</a></div></div></section>
    <div class="cities-served"><h3>Cities We Serve in UK</h3><div class="cities-list"><span class="city-tag">London (8.9M)</span><span class="city-tag">Manchester (553K)</span><span class="city-tag">Birmingham (1.1M)</span><span class="city-tag">Leeds (800K)</span><span class="city-tag">Glasgow (635K)</span><span class="city-tag">Liverpool (498K)</span></div></div>
    <section class="city-services"><h2 class="city-section-title">Our Services in UK</h2><div class="services-city-grid"><div class="city-service-card"><h3>Instagram Followers</h3><div class="price">Starting at <span>$2.99</span></div><p>Real followers for your profile</p></div><div class="city-service-card"><h3>Instagram Likes</h3><div class="price">Starting at <span>$1.49</span></div><p>High-quality likes from active accounts</p></div><div class="city-service-card"><h3>YouTube Views</h3><div class="price">Starting at <span>$3.99</span></div><p>Fast YouTube views delivery</p></div><div class="city-service-card"><h3>TikTok Followers</h3><div class="price">Starting at <span>$2.49</span></div><p>Grow your TikTok audience</p></div></div></section>
    <section class="city-features"><h2 class="city-section-title">Why Choose Us in UK?</h2><div class="features-city-grid"><div class="city-feature-card"><div class="city-feature-icon">⚡</div><h3>Instant Delivery</h3><p>Most orders start within minutes</p></div><div class="city-feature-card"><div class="city-feature-icon">🔄</div><h3>30-Day Refill</h3><p>Free refill if numbers drop</p></div><div class="city-feature-card"><div class="city-feature-icon">🔒</div><h3>Secure Payment</h3><p>PayPal, Cards, Bank Transfer</p></div><div class="city-feature-card"><div class="city-feature-icon">💬</div><h3>24/7 Support</h3><p>UK-based support team</p></div></div></section>
    <section class="city-testimonials"><h2 class="city-section-title">What UK Customers Say</h2><div class="city-testimonial"><p class="city-testimonial-text">"Excellent service! Fast delivery and great support for London influencers."</p><div class="city-testimonial-author"><strong>James W.</strong><span>— Influencer, London</span></div></div><div class="city-testimonial"><p class="city-testimonial-text">"Best SMM panel in UK! Helped our Manchester business grow significantly."</p><div class="city-testimonial-author"><strong>Emma T.</strong><span>— Business Owner, Manchester</span></div></div></section>
    <section class="city-cta"><div class="container"><h2>Ready to Grow in UK?</h2><p>Join thousands of satisfied UK customers.</p><a href="/register" class="btn">Create Free Account</a></div></section>
    <footer class="footer"><div class="container"><div class="footer-content"><div class="footer-section"><h4><?= htmlspecialchars($siteName) ?></h4><p>Best SMM panel in UK and worldwide.</p></div></div><div class="footer-bottom"><p>&copy; <?= date('Y') ?> <?= htmlspecialchars($siteName) ?>. All rights reserved.</p></div></div></footer>
    <script src="<?= $siteUrl ?>/assets/js/main.js"></script>
</body>
</html>