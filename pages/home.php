<?php
/**
 * Homepage - SEO Optimized Landing Page
 *
 * Features:
 * - Dynamic SEO meta tags
 * - JSON-LD structured data (Organization, LocalBusiness, SoftwareApplication)
 * - OpenGraph and Twitter Card meta tags
 * - GEO-LLM-SEO placeholders for city-based content
 *
 * @version 1.0.0
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../components/svg_icons.php';

// =====================================================
// SEO CONFIGURATION
// =====================================================

// Default SEO values
$siteName = SITE_NAME;
$siteUrl = SITE_URL;
$siteDescription = "Grow your social media presence instantly with our premium SMM panel. Buy Instagram followers, likes, views, Facebook likes, TikTok followers, and YouTube subscribers. Fast delivery, 30-day refill guarantee, and 24/7 support.";
$siteKeywords = "SMM panel, buy followers, buy likes, buy views, Instagram followers, Facebook likes, TikTok followers, YouTube subscribers, social media marketing, best SMM panel India";

// Dynamic page title
$pageTitle = "Buy Instagram Followers, Likes, Views & More | Best SMM Panel";
$pageTitleWithSite = $pageTitle . " | " . $siteName;

// GEO-LLM-SEO: Uncomment and customize for city-specific SEO
// $city = "Delhi"; // Change to your target city
// $cityTitle = "Buy Instagram Followers in {$city} - Fast Delivery | {$siteName}";
// $cityDescription = "Get real Instagram followers, likes, and views delivered instantly in {$city}. Best SMM panel service in {$city}, India with 30-day refill guarantee.";

// =====================================================
// JSON-LD STRUCTURED DATA
// =====================================================

// Organization schema
$organizationSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => $siteName,
    'url' => $siteUrl,
    'description' => $siteDescription,
    'logo' => $siteUrl . '/assets/images/logo.png',
    'contactPoint' => [
        '@type' => 'ContactPoint',
        'telephone' => '+91-9876543210',
        'contactType' => 'customer service',
        'availableHours' => '24/7',
        'areaServed' => 'IN',
        'availableLanguage' => ['English', 'Hindi']
    ],
    'sameAs' => [
        'https://facebook.com/smmpanel',
        'https://instagram.com/smmpanel_official',
        'https://twitter.com/smmpanel',
        'https://youtube.com/@smmpanel'
    ]
];

// LocalBusiness schema (for India-specific SEO)
$localBusinessSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'LocalBusiness',
    'name' => $siteName,
    'image' => $siteUrl . '/assets/images/logo.png',
    'address' => [
        '@type' => 'PostalAddress',
        'addressCountry' => 'IN',
        'addressRegion' => 'Delhi' // Update with actual region
    ],
    'openingHours' => 'Mo-Su 00:00-24:00',
    'priceRange' => '$$'
];

// WebApplication schema for the SMM Panel
$webAppSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'WebApplication',
    'name' => $siteName,
    'description' => 'Professional Social Media Marketing panel for Instagram, Facebook, TikTok, and YouTube',
    'url' => $siteUrl,
    'applicationCategory' => 'BusinessApplication',
    'operatingSystem' => 'Web Browser',
    'offers' => [
        '@type' => 'Offer',
        'price' => '0',
        'priceCurrency' => 'USD',
        'availability' => 'https://schema.org/InStock'
    ],
    'browserRequirements' => 'Requires JavaScript enabled',
    'softwareVersion' => '1.0.0'
];

// Get featured services for schema
$featuredServices = Database::fetchAll(
    "SELECT name, type, min_quantity, max_quantity, our_rate, platform
     FROM services
     WHERE status = 'active'
     ORDER BY display_order ASC
     LIMIT 6"
);

$serviceSchema = [];
foreach ($featuredServices as $service) {
    $serviceSchema[] = [
        '@type' => 'Offer',
        'name' => $service['name'],
        'description' => 'Buy ' . strtolower($service['name']) . ' starting at $' . number_format($service['our_rate'] * $service['min_quantity'], 2),
        'price' => number_format($service['our_rate'] * $service['min_quantity'], 2),
        'priceCurrency' => 'USD',
        'availability' => 'https://schema.org/InStock',
        'url' => $siteUrl . '/services?service=' . urlencode($service['name'])
    ];
}

// FAQ Schema (common questions)
$faqSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => [
        [
            '@type' => 'Question',
            'name' => 'What is an SMM panel?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'An SMM (Social Media Marketing) panel is a platform that allows you to buy social media services like followers, likes, views, and comments for various platforms including Instagram, Facebook, TikTok, and YouTube.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'Is it safe to buy followers and likes?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Yes, our services are designed to be safe and comply with social media platform terms of service. We provide high-quality, real-like engagement that helps grow your social media presence organically.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'How long does delivery take?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Delivery times vary depending on the service and quantity ordered. Most orders start delivering within 1-24 hours. Large orders may take 2-5 days for complete delivery.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'Do you offer refill guarantee?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Yes, most of our services come with a 30-day refill guarantee. If your followers or likes drop within 30 days, we will refill them at no additional cost.'
            ]
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="en-IN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Primary Meta Tags -->
    <title><?= htmlspecialchars($pageTitleWithSite) ?></title>
    <meta name="title" content="<?= htmlspecialchars($pageTitleWithSite) ?>">
    <meta name="description" content="<?= htmlspecialchars($siteDescription) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($siteKeywords) ?>">
    <meta name="author" content="<?= htmlspecialchars($siteName) ?>">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    <meta name="revisit-after" content="1 day">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= htmlspecialchars($siteUrl) ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars($siteUrl) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($siteDescription) ?>">
    <meta property="og:image" content="<?= $siteUrl ?>/assets/images/og-image.png">
    <meta property="og:site_name" content="<?= htmlspecialchars($siteName) ?>">
    <meta property="og:locale" content="en_IN">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= htmlspecialchars($siteUrl) ?>">
    <meta property="twitter:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="twitter:description" content="<?= htmlspecialchars($siteDescription) ?>">
    <meta property="twitter:image" content="<?= $siteUrl ?>/assets/images/og-image.png">

    <!-- Additional SEO Tags -->
    <meta name="theme-color" content="#6366f1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <!-- GEO-LLM-SEO: City-specific meta tags (uncomment for city pages) -->
    <!--
    <meta name="geo.region" content="IN-DL">
    <meta name="geo.placename" content="Delhi">
    <meta name="ICBM" content="28.6139, 77.2090">
    -->

    <!-- JSON-LD Structured Data -->
    <?= jsonLd($organizationSchema) ?>
    <?= jsonLd($localBusinessSchema) ?>
    <?= jsonLd($webAppSchema) ?>
    <?= jsonLd($faqSchema) ?>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="<?= $siteUrl ?>/assets/css/index.css">

    <!-- Security Headers (via .htaccess or server config recommended) -->
</head>
<body>
    <!-- JSON-LD for BreadcrumbList -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "<?= $siteUrl ?>"
            }
        ]
    }
    </script>

    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="/" class="logo">
                    <span class="logo-icon"><?= getIcon('logo') ?></span>
                    <span class="logo-text"><?= htmlspecialchars($siteName) ?></span>
                </a>
                <button class="hamburger" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <button class="dark-mode-toggle" id="darkModeToggle" aria-label="Toggle dark mode">
                    <?= getIcon('moon') ?>
                </button>
                <ul class="nav-links">
                    <li><a href="/" class="nav-link">Home</a></li>
                    <li><a href="/services" class="nav-link">Services</a></li>
                    <li><a href="/faq" class="nav-link">FAQ</a></li>
                    <li><a href="/terms" class="nav-link">Terms</a></li>
                    <li><a href="/privacy" class="nav-link">Privacy</a></li>
                    <li><a href="/login" class="btn btn-outline">Login</a></li>
                    <li><a href="/register" class="btn btn-primary">Sign Up</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">Boost Your Social Media Presence Instantly</h1>
                    <p class="hero-subtitle">The #1 SMM panel for Instagram, Facebook, TikTok & YouTube. Real followers, likes, views at unbeatable prices.</p>

                    <div class="hero-cta">
                        <a href="/services" class="btn btn-primary btn-lg">Browse Services</a>
                        <a href="/register" class="btn btn-secondary btn-lg">Get Started Free</a>
                    </div>

                    <div class="hero-stats">
                        <div class="stat">
                            <span class="stat-number">50K+</span>
                            <span class="stat-label">Orders Delivered</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">10K+</span>
                            <span class="stat-label">Happy Customers</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">24/7</span>
                            <span class="stat-label">Support</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features">
            <div class="container">
                <h2 class="section-title">Why Choose <?= htmlspecialchars($siteName) ?>?</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon"><?= getIcon('zap') ?></div>
                        <h3>Fast Delivery</h3>
                        <p>Most orders start within minutes. Get your followers and likes delivered quickly.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><?= getIcon('refresh') ?></div>
                        <h3>30-Day Refill</h3>
                        <p>We offer refill guarantee on most services. If numbers drop, we refill them free.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><?= getIcon('shield') ?></div>
                        <h3>Secure Payments</h3>
                        <p>Your payments are secure with encryption. Multiple payment options available.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><?= getIcon('wallet') ?></div>
                        <h3>Best Prices</h3>
                        <p>Competitive pricing with markup as low as 20%. Great value for quality service.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Preview -->
        <section class="services-preview">
            <div class="container">
                <h2 class="section-title">Popular Services</h2>
                <div class="services-grid">
                    <?php foreach ($featuredServices as $service): ?>
                    <a href="/services?service=<?= urlencode($service['name']) ?>" class="service-card">
                        <div class="service-icon">
                            <?= platformIcon($service['platform']) ?>
                        </div>
                        <h3><?= htmlspecialchars($service['name']) ?></h3>
                        <p class="service-price">From $<?= number_format($service['our_rate'] * $service['min_quantity'], 2) ?></p>
                        <span class="service-cta">View Details →</span>
                    </a>
                    <?php endforeach; ?>
                </div>
                <div class="section-cta">
                    <a href="/services" class="btn btn-primary">View All Services</a>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section class="how-it-works">
            <div class="container">
                <h2 class="section-title">How It Works</h2>
                <div class="steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h3>Create Account</h3>
                        <p>Sign up for free and get access to all our services.</p>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <h3>Add Funds</h3>
                        <p>Add balance to your account using UPI or cards.</p>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <h3>Place Order</h3>
                        <p>Select service, enter link, and quantity. Done!</p>
                    </div>
                    <div class="step">
                        <div class="step-number">4</div>
                        <h3>Watch Growth</h3>
                        <p>See your followers and engagement grow instantly.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section (SEO optimized) -->
        <section class="faq-section">
            <div class="container">
                <h2 class="section-title">Frequently Asked Questions</h2>
                <div class="faq-list">
                    <details class="faq-item">
                        <summary>What is an SMM panel?</summary>
                        <p>An SMM (Social Media Marketing) panel is a platform that allows you to buy social media services like followers, likes, views, and comments for various platforms including Instagram, Facebook, TikTok, and YouTube.</p>
                    </details>
                    <details class="faq-item">
                        <summary>Is it safe to buy followers and likes?</summary>
                        <p>Yes, our services are designed to be safe and comply with social media platform terms of service. We provide high-quality, real-like engagement that helps grow your social media presence organically.</p>
                    </details>
                    <details class="faq-item">
                        <summary>How long does delivery take?</summary>
                        <p>Delivery times vary depending on the service and quantity ordered. Most orders start delivering within 1-24 hours. Large orders may take 2-5 days for complete delivery.</p>
                    </details>
                    <details class="faq-item">
                        <summary>Do you offer refill guarantee?</summary>
                        <p>Yes, most of our services come with a 30-day refill guarantee. If your followers or likes drop within 30 days, we will refill them at no additional cost.</p>
                    </details>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container">
                <h2>Ready to Grow Your Social Media?</h2>
                <p>Join thousands of satisfied customers and boost your online presence today.</p>
                <a href="/register" class="btn btn-primary btn-lg">Get Started Now</a>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4><?= htmlspecialchars($siteName) ?></h4>
                    <p>Your trusted SMM panel for Instagram, Facebook, TikTok, and YouTube services.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/services">Services</a></li>
                        <li><a href="/faq">FAQ</a></li>
                        <li><a href="/terms">Terms of Service</a></li>
                        <li><a href="/privacy">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p>Email: <?= SITE_EMAIL ?></p>
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