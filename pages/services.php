<?php
/**
 * Services Page - SEO Optimized
 *
 * Features:
 * - SEO-friendly service cards
 * - JSON-LD Offer/Service schema
 * - Platform filtering
 * - Price display with markup
 *
 * @version 1.0.0
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

// Get filter from query string
$platform = $_GET['platform'] ?? 'all';
$search = $_GET['search'] ?? '';

// Build query
$where = "status = 'active'";
$params = [];

if ($platform !== 'all' && in_array($platform, ['instagram', 'facebook', 'tiktok', 'youtube'])) {
    $where .= " AND platform = ?";
    $params[] = $platform;
}

if (!empty($search)) {
    $where .= " AND (name LIKE ? OR category LIKE ?)";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

$services = Database::fetchAll(
    "SELECT * FROM services WHERE {$where} ORDER BY display_order ASC, platform ASC",
    $params
);

// Group services by platform
$groupedServices = [];
foreach ($services as $service) {
    $groupedServices[$service['platform']][] = $service;
}

// SEO Configuration
$pageTitle = ($platform !== 'all')
    ? "Buy " . ucfirst($platform) . " Followers, Likes & Views | Best SMM Panel"
    : "All SMM Services | Instagram, Facebook, TikTok, YouTube";

$siteName = SITE_NAME;
$siteUrl = SITE_URL;
$pageDescription = "Best SMM panel services. Buy " . ($platform !== 'all' ? $platform : "Instagram, Facebook, TikTok, YouTube") . " followers, likes, views at lowest prices with 30-day refill guarantee.";

// Generate JSON-LD for services
$serviceSchemas = [];
foreach ($services as $service) {
    $minPrice = $service['our_rate'] * $service['min_quantity'];
    $maxPrice = $service['our_rate'] * $service['max_quantity'];

    $serviceSchemas[] = [
        '@context' => 'https://schema.org',
        '@type' => 'Offer',
        'name' => $service['name'],
        'description' => $service['description'] ?? "Buy {$service['name']} - Fast delivery and refill guarantee",
        'price' => number_format($minPrice, 2),
        'priceCurrency' => 'USD',
        'priceRange' => "$" . number_format($minPrice, 2) . " - $" . number_format($maxPrice, 2),
        'availability' => 'https://schema.org/InStock',
        'validFrom' => date('c'),
        'seller' => [
            '@type' => 'Organization',
            'name' => $siteName
        ],
        'category' => $service['category'],
        'additionalProperty' => [
            [
                '@type' => 'PropertyValue',
                'name' => 'Platform',
                'value' => ucfirst($service['platform'])
            ],
            [
                '@type' => 'PropertyValue',
                'name' => 'Min Quantity',
                'value' => $service['min_quantity']
            ],
            [
                '@type' => 'PropertyValue',
                'name' => 'Max Quantity',
                'value' => $service['max_quantity']
            ],
            [
                '@type' => 'PropertyValue',
                'name' => 'Refill Available',
                'value' => $service['refill'] ? 'Yes' : 'No'
            ]
        ]
    ];
}

// Platform icons and names
$platforms = [
    'instagram' => ['icon' => '📸', 'name' => 'Instagram', 'color' => '#E4405F'],
    'facebook' => ['icon' => '📘', 'name' => 'Facebook', 'color' => '#1877F2'],
    'tiktok' => ['icon' => '🎵', 'name' => 'TikTok', 'color' => '#000000'],
    'youtube' => ['icon' => '▶️', 'name' => 'YouTube', 'color' => '#FF0000'],
    'twitter' => ['icon' => '🐦', 'name' => 'Twitter', 'color' => '#1DA1F2'],
    'linkedin' => ['icon' => '💼', 'name' => 'LinkedIn', 'color' => '#0A66C2'],
];
?>
<!DOCTYPE html>
<html lang="en-IN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Primary Meta Tags -->
    <title><?= htmlspecialchars($pageTitle) ?> | <?= htmlspecialchars($siteName) ?></title>
    <meta name="title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="keywords" content="SMM panel, buy followers, buy likes, buy views, social media marketing, <?= $platform !== 'all' ? $platform : 'Instagram Facebook TikTok YouTube' ?>">
    <meta name="robots" content="index, follow">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= $siteUrl ?>/services<?= $platform !== 'all' ? '?platform=' . $platform : '' ?>">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $siteUrl ?>/services">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta property="og:site_name" content="<?= htmlspecialchars($siteName) ?>">

    <!-- JSON-LD Structured Data for Services -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ItemList",
        "name": "<?= htmlspecialchars($pageTitle) ?>",
        "description": "<?= htmlspecialchars($pageDescription) ?>",
        "url": "<?= $siteUrl ?>/services",
        "numberOfItems": <?= count($services) ?>,
        "itemListOrder": "https://schema.org/ItemListOrderAscending"
    }
    </script>

    <!-- Services Collection Schema -->
    <?php foreach (array_slice($serviceSchemas, 0, 10) as $schema): ?>
    <?= jsonLd($schema) ?>
    <?php endforeach; ?>

    <!-- Breadcrumb Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "Home", "item": "<?= $siteUrl ?>"},
            {"@type": "ListItem", "position": 2, "name": "Services", "item": "<?= $siteUrl ?>/services"}
        ]
    }
    </script>

    <link rel="stylesheet" href="<?= $siteUrl ?>/assets/css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="/" class="logo">
                    <span class="logo-icon">📊</span>
                    <span class="logo-text"><?= htmlspecialchars($siteName) ?></span>
                </a>
                <ul class="nav-links">
                    <li><a href="/" class="active">Home</a></li>
                    <li><a href="/services">Services</a></li>
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/terms">Terms</a></li>
                    <li><a href="/login" class="btn btn-outline">Login</a></li>
                    <li><a href="/register" class="btn btn-primary">Sign Up</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="services-page">
        <div class="container">
            <div class="page-header">
                <h1>Our Services</h1>
                <p>Choose from our wide range of social media marketing services</p>
            </div>

            <!-- Filters -->
            <div class="filters">
                <div class="filter-tabs">
                    <a href="/services" class="filter-tab <?= $platform === 'all' ? 'active' : '' ?>">All</a>
                    <?php foreach ($platforms as $key => $info): ?>
                    <a href="/services?platform=<?= $key ?>" class="filter-tab <?= $platform === $key ? 'active' : '' ?>">
                        <?= $info['icon'] ?> <?= $info['name'] ?>
                    </a>
                    <?php endforeach; ?>
                </div>

                <form class="search-form" method="GET">
                    <input type="hidden" name="platform" value="<?= htmlspecialchars($platform) ?>">
                    <input type="text" name="search" placeholder="Search services..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>

            <!-- Services Grid -->
            <div class="services-container">
                <?php if (empty($services)): ?>
                <div class="no-results">
                    <p>No services found. Try a different filter or search term.</p>
                </div>
                <?php else: ?>

                <?php foreach ($groupedServices as $platformName => $platformServices): ?>
                <section class="platform-section" id="<?= $platformName ?>">
                    <h2 class="platform-title">
                        <?= $platforms[$platformName]['icon'] ?> <?= $platforms[$platformName]['name'] ?> Services
                    </h2>

                    <div class="services-grid">
                        <?php foreach ($platformServices as $service):
                            $minPrice = $service['our_rate'] * $service['min_quantity'];
                            $maxPrice = $service['our_rate'] * $service['max_quantity'];
                        ?>
                        <article class="service-card" itemscope itemtype="https://schema.org/Offer">
                            <meta itemprop="price" content="<?= number_format($minPrice, 2) ?>">
                            <meta itemprop="priceCurrency" content="USD">

                            <div class="service-header">
                                <span class="service-icon"><?= $platforms[$service['platform']]['icon'] ?? '🌐' ?></span>
                                <div class="service-info">
                                    <h3 itemprop="name"><?= htmlspecialchars($service['name']) ?></h3>
                                    <span class="service-category"><?= htmlspecialchars($service['category']) ?></span>
                                </div>
                            </div>

                            <div class="service-body">
                                <p class="service-description" itemprop="description">
                                    <?= htmlspecialchars($service['description'] ?? 'High-quality service with fast delivery and refill guarantee.') ?>
                                </p>

                                <div class="service-meta">
                                    <div class="meta-item">
                                        <span class="meta-label">Service ID</span>
                                        <span class="meta-value">#<?= $service['smmwiz_id'] ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Rate/1000</span>
                                        <span class="meta-value">$<?= number_format($service['rate'], 4) ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Your Price/1000</span>
                                        <span class="meta-value" style="color:#10b981;font-weight:600;">$<?= number_format($service['our_rate'], 4) ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Min Order</span>
                                        <span class="meta-value"><?= number_format($service['min_quantity']) ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Max Order</span>
                                        <span class="meta-value"><?= number_format($service['max_quantity']) ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Refill</span>
                                        <span class="meta-value <?= $service['refill'] ? 'text-success' : 'text-muted' ?>">
                                            <?= $service['refill'] ? '✓ Yes' : '✗ No' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="service-footer">
                                <div class="price-info">
                                    <span class="price-label">Starting from</span>
                                    <span class="price-value">$<?= number_format($minPrice, 2) ?></span>
                                </div>
                                <a href="/order?service=<?= $service['id'] ?>" class="btn btn-primary">Order Now</a>
                            </div>

                            <!-- Structured Data (hidden) -->
                            <meta itemprop="availability" content="https://schema.org/InStock">
                        </article>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endforeach; ?>

                <?php endif; ?>
            </div>

            <!-- Info Section -->
            <div class="services-info">
                <h2>How Our Services Work</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h3>1. Select Service</h3>
                        <p>Browse our services and choose the one that fits your needs. Filter by platform to find exactly what you're looking for.</p>
                    </div>
                    <div class="info-card">
                        <h3>2. Enter Details</h3>
                        <p>Provide your profile URL or post link and select the quantity you want to order.</p>
                    </div>
                    <div class="info-card">
                        <h3>3. Make Payment</h3>
                        <p>Pay securely using your account balance. We accept UPI, cards, and other payment methods.</p>
                    </div>
                    <div class="info-card">
                        <h3>4. Watch Growth</h3>
                        <p>Your order starts processing immediately. Track your order status in your dashboard.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4><?= htmlspecialchars($siteName) ?></h4>
                    <p>Your trusted SMM panel for all social media services.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/services">Services</a></li>
                        <li><a href="/faq">FAQ</a></li>
                        <li><a href="/terms">Terms</a></li>
                        <li><a href="/privacy">Privacy</a></li>
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