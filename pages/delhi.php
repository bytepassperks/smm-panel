<?php
/**
 * City Landing Page - Delhi
 * GEO SEO optimized for Delhi, India
 *
 * @version 1.0.0
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../components/svg_icons.php';

$siteName = SITE_NAME;
$siteUrl = SITE_URL;

// City Configuration
$city = 'Delhi';
$cityName = 'Delhi';
$state = 'Delhi';
$region = 'North India';

// SEO - City Specific
$pageTitle = "Best SMM Panel in {$cityName} | Buy Followers, Likes & Views";
$pageDescription = "Get instant social media followers, likes, and views in {$cityName}, Delhi. Best SMM panel service in {$cityName} with 30-day refill guarantee. Fast delivery, secure payments.";

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
    ['name' => 'Amit S.', 'role' => 'Influencer', 'text' => 'Great service! Got 5000 followers in 3 days. Highly recommended for influencers in Delhi.'],
    ['name' => 'Priya M.', 'role' => 'Business Owner', 'text' => 'Best SMM panel in Delhi. Their delivery is super fast and prices are very competitive.'],
    ['name' => 'Rahul K.', 'role' => 'Content Creator', 'text' => 'Excellent service! The refill feature is very helpful. Will definitely order again.']
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
    <meta name="keywords" content="SMM panel Delhi, buy Instagram followers Delhi, best SMM panel {$cityName}, social media marketing {$cityName}, SMM panel India, buy followers Delhi NCR">
    <meta name="robots" content="index, follow">
    <meta name="geo.region" content="IN-DL">
    <meta name="geo.placename" content="<?= htmlspecialchars($cityName) ?>">

    <link rel="canonical" href="<?= $siteUrl ?>/delhi">

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $siteUrl ?>/delhi">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">

    <!-- LocalBusiness Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "<?= htmlspecialchars($siteName) ?> - <?= $cityName ?>",
        "description": "Best SMM panel in <?= $cityName ?>, Delhi. Buy Instagram followers, likes, views, and more.",
        "url": "<?= $siteUrl ?>/delhi",
        "areaServed": {
            "@type": "City",
            "name": "<?= $cityName ?>"
        },
        "address": {
            "@type": "PostalAddress",
            "addressRegion": "Delhi",
            "addressCountry": "IN"
        },
        "priceRange": "$$"
    }
    </script>

    <link rel="stylesheet" href="<?= $siteUrl ?>/assets/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: var(--bg-primary); font-family: 'Inter', sans-serif; }
        .city-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 5rem 0;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .city-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 70%, rgba(255,255,255,0.1) 0%, transparent 50%),
                        radial-gradient(circle at 70% 30%, rgba(255,255,255,0.1) 0%, transparent 50%);
        }
        .city-hero-content {
            position: relative;
            z-index: 1;
        }
        .city-badge {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        .city-hero h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        .city-hero p {
            font-size: 1.25rem;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto 2rem;
        }
        .city-hero-btns {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .city-services {
            padding: 4rem 0;
            max-width: 1100px;
            margin: 0 auto;
        }
        .city-section-title {
            text-align: center;
            font-size: 2rem;
            color: var(--text-primary);
            margin-bottom: 3rem;
        }
        .services-city-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            padding: 0 1.5rem;
        }
        .city-service-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.75rem;
            transition: all 0.3s ease;
        }
        .city-service-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.15);
        }
        .city-service-card h3 {
            color: var(--text-primary);
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }
        .city-service-card .price {
            color: var(--primary);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .city-service-card .price span {
            font-size: 0.9rem;
            font-weight: 400;
        }
        .city-service-card p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        .city-features {
            background: var(--bg-secondary);
            padding: 4rem 0;
        }
        .features-city-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        .city-feature-card {
            text-align: center;
            padding: 2rem;
        }
        .city-feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .city-feature-card h3 {
            color: var(--text-primary);
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        .city-feature-card p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        .city-testimonials {
            padding: 4rem 0;
            max-width: 900px;
            margin: 0 auto;
        }
        .city-testimonial {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .city-testimonial-text {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1rem;
            font-style: italic;
        }
        .city-testimonial-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .city-testimonial-author strong {
            color: var(--text-primary);
        }
        .city-testimonial-author span {
            color: var(--text-muted);
            font-size: 0.85rem;
        }
        .city-cta {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 4rem 0;
            text-align: center;
            color: white;
        }
        .city-cta h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .city-cta p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }
        body.dark-mode .city-service-card {
            background: #1e293b;
        }
        body.dark-mode .city-testimonial {
            background: #1e293b;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="/" class="logo">
                    <span class="logo-icon"><?= getIcon('logo') ?></span>
                    <span class="logo-text"><?= htmlspecialchars($siteName) ?></span>
                </a>
                <ul class="nav-links">
                    <li><a href="/">Home</a></li>
                    <li><a href="/services">Services</a></li>
                    <li><a href="/pricing">Pricing</a></li>
                    <li><a href="/blog">Blog</a></li>
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/login" class="btn btn-outline">Login</a></li>
                    <li><a href="/register" class="btn btn-primary">Sign Up</a></li>
                </ul>
                <button class="hamburger" aria-label="Menu"><span></span><span></span><span></span></button>
            </nav>
        </div>
    </header>

    <section class="city-hero">
        <div class="container city-hero-content">
            <div class="city-badge">📍 Best SMM Panel in <?= $cityName ?></div>
            <h1>Grow Your Social Media in <?= $cityName ?></h1>
            <p>Join thousands of creators and businesses in <?= $cityName ?> who trust us for their social media growth. Get instant delivery and 30-day refill guarantee.</p>
            <div class="city-hero-btns">
                <a href="/register" class="btn btn-primary" style="background: white; color: #667eea;">Get Started Free</a>
                <a href="/services" class="btn btn-secondary" style="border-color: white; color: white;">View Services</a>
            </div>
        </div>
    </section>

    <section class="city-services">
        <h2 class="city-section-title">Our Services in <?= $cityName ?></h2>
        <div class="services-city-grid">
            <?php foreach ($services as $service): ?>
            <div class="city-service-card">
                <h3><?= $service['name'] ?></h3>
                <div class="price">Starting at <span><?= $service['starting'] ?></span></div>
                <p><?= $service['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="city-features">
        <h2 class="city-section-title">Why Choose Us in <?= $cityName ?>?</h2>
        <div class="features-city-grid">
            <?php foreach ($features as $feature): ?>
            <div class="city-feature-card">
                <div class="city-feature-icon"><?= $feature['icon'] ?></div>
                <h3><?= $feature['title'] ?></h3>
                <p><?= $feature['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="city-testimonials">
        <h2 class="city-section-title">What <?= $cityName ?> Customers Say</h2>
        <?php foreach ($testimonials as $testimonial): ?>
        <div class="city-testimonial">
            <p class="city-testimonial-text">"<?= $testimonial['text'] ?>"</p>
            <div class="city-testimonial-author">
                <strong><?= $testimonial['name'] ?></strong>
                <span>— <?= $testimonial['role'] ?>, <?= $cityName ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </section>

    <section class="city-cta">
        <div class="container">
            <h2>Ready to Grow in <?= $cityName ?>?</h2>
            <p>Join 10,000+ satisfied customers across India. Start your journey today.</p>
            <a href="/register" class="btn btn-primary" style="background: white; color: #667eea; padding: 14px 32px; font-size: 1.1rem; font-weight: 600;">Create Free Account</a>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4><?= htmlspecialchars($siteName) ?></h4>
                    <p>Best SMM panel in <?= $cityName ?> and all across India.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/services">Services</a></li>
                        <li><a href="/pricing">Pricing</a></li>
                        <li><a href="/blog">Blog</a></li>
                        <li><a href="/faq">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($siteName) ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="<?= $siteUrl ?>/assets/js/main.js"></script>
</body>
</html>