<?php
/**
 * Pricing Page - Membership Plans
 *
 * @version 1.0.0
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../components/svg_icons.php';

$siteName = SITE_NAME;
$siteUrl = SITE_URL;

// SEO Configuration
$pageTitle = "Pricing Plans | SMM Panel Membership Options";
$pageDescription = "Choose the best SMM panel plan for your needs. Starting from free to enterprise tiers with wholesale rates, API access, and dedicated support.";

// Membership tiers
$tiers = [
    [
        'name' => 'Free',
        'price' => '0',
        'period' => 'Forever',
        'description' => 'Perfect for testing our services',
        'features' => [
            'Access to all services',
            'Basic customer support',
            'Standard delivery speed',
            '5% profit margin',
            'Email support'
        ],
        'cta' => 'Get Started',
        'popular' => false,
        'color' => '#6b7280'
    ],
    [
        'name' => 'Pro',
        'price' => '29',
        'period' => 'month',
        'description' => 'Best for individual resellers',
        'features' => [
            'Everything in Free',
            '15% profit margin',
            'Priority support',
            'Faster delivery',
            'API access',
            'Order history export'
        ],
        'cta' => 'Go Pro',
        'popular' => true,
        'color' => '#6366f1'
    ],
    [
        'name' => 'Reseller',
        'price' => '79',
        'period' => 'month',
        'description' => 'For serious resellers & agencies',
        'features' => [
            'Everything in Pro',
            '25% profit margin',
            'Dedicated account manager',
            'White-label solution',
            'Bulk ordering',
            'Custom rates for clients',
            'Priority API access'
        ],
        'cta' => 'Become Reseller',
        'popular' => false,
        'color' => '#8b5cf6'
    ],
    [
        'name' => 'Enterprise',
        'price' => '199',
        'period' => 'month',
        'description' => 'For large agencies & businesses',
        'features' => [
            'Everything in Reseller',
            '40% profit margin',
            'Custom API integration',
            'Dedicated server',
            'SLA guarantee',
            '24/7 phone support',
            'Monthly billing options'
        ],
        'cta' => 'Contact Sales',
        'popular' => false,
        'color' => '#f59e0b'
    ]
];

// FAQ for pricing
$faqs = [
    [
        'question' => 'Can I upgrade my plan later?',
        'answer' => 'Yes! You can upgrade or downgrade your plan at any time. Changes take effect on the next billing cycle.'
    ],
    [
        'question' => 'Is there a free trial?',
        'answer' => 'The Free plan lets you test all features indefinitely. Pro and higher plans have no trial but include a 7-day refund policy.'
    ],
    [
        'question' => 'What payment methods do you accept?',
        'answer' => 'We accept UPI, Paytm, bank transfer, credit/debit cards, and cryptocurrency.'
    ],
    [
        'question' => 'Can I cancel anytime?',
        'answer' => 'Yes, you can cancel your subscription anytime. No questions asked, no cancellation fees.'
    ]
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
    <meta name="robots" content="index, follow">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= $siteUrl ?>/pricing">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $siteUrl ?>/pricing">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">

    <!-- JSON-LD -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "PriceSpecification",
        "priceCurrency": "USD",
        "minPrice": "0",
        "maxPrice": "199"
    }
    </script>

    <link rel="stylesheet" href="<?= $siteUrl ?>/assets/css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: var(--bg-primary); font-family: 'Inter', sans-serif; }
        .pricing-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 4rem 0;
            text-align: center;
            color: white;
        }
        .pricing-hero h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        .pricing-hero p {
            font-size: 1.125rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 1.5rem;
        }
        .pricing-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            position: relative;
            transition: all 0.3s ease;
        }
        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .pricing-card.popular {
            border-color: var(--primary);
            transform: scale(1.05);
        }
        .pricing-card.popular:hover {
            transform: scale(1.05) translateY(-5px);
        }
        .popular-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--primary);
            color: white;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .pricing-tier-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }
        .pricing-tier-desc {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        .pricing-amount {
            font-size: 3rem;
            font-weight: 800;
            color: var(--text-primary);
        }
        .pricing-amount span {
            font-size: 1rem;
            font-weight: 400;
            color: var(--text-secondary);
        }
        .pricing-features {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
            text-align: left;
        }
        .pricing-features li {
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-secondary);
        }
        .pricing-features li::before {
            content: '✓';
            color: #10b981;
            font-weight: bold;
        }
        .pricing-cta {
            display: block;
            padding: 1rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            text-align: center;
        }
        .pricing-cta.btn-outline {
            border: 2px solid var(--border-color);
            color: var(--text-primary);
        }
        .pricing-cta.btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
        }
        .pricing-cta.btn-primary {
            background: var(--primary);
            color: white;
        }
        .pricing-cta.btn-primary:hover {
            background: var(--primary-dark);
        }
        body.dark-mode .pricing-card {
            background: #1e293b;
        }
        body.dark-mode .pricing-features li {
            border-color: #334155;
        }
        .pricing-faq {
            max-width: 800px;
            margin: 4rem auto;
            padding: 0 1.5rem;
        }
        .pricing-faq h2 {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
            color: var(--text-primary);
        }
        .faq-item {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .faq-question {
            padding: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .faq-answer {
            padding: 0 1.25rem 1.25rem;
            color: var(--text-secondary);
            display: none;
        }
        .faq-item.open .faq-answer {
            display: block;
        }
        .pricing-cta-section {
            text-align: center;
            margin-top: 3rem;
            color: var(--text-secondary);
        }
        .pricing-cta-section a {
            color: var(--primary);
            font-weight: 600;
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
                    <li><a href="/pricing" class="active">Pricing</a></li>
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/login" class="btn btn-outline">Login</a></li>
                    <li><a href="/register" class="btn btn-primary">Sign Up</a></li>
                </ul>
                <button class="hamburger" aria-label="Menu">
                    <span></span><span></span><span></span>
                </button>
            </nav>
        </div>
    </header>

    <div class="pricing-hero">
        <div class="container">
            <h1>Choose Your Plan</h1>
            <p>Start free and scale as you grow. All plans include access to our complete service catalog.</p>
        </div>
    </div>

    <div class="pricing-grid">
        <?php foreach ($tiers as $tier): ?>
        <div class="pricing-card <?= $tier['popular'] ? 'popular' : '' ?>">
            <?php if ($tier['popular']): ?>
            <div class="popular-badge">Most Popular</div>
            <?php endif; ?>
            <div class="pricing-tier-name" style="color: <?= $tier['color'] ?>"><?= $tier['name'] ?></div>
            <div class="pricing-tier-desc"><?= $tier['description'] ?></div>
            <div class="pricing-amount">
                $<?= $tier['price'] ?>
                <?php if ($tier['price'] > 0): ?>
                <span>/<?= $tier['period'] ?></span>
                <?php else: ?>
                <span><?= $tier['period'] ?></span>
                <?php endif; ?>
            </div>
            <ul class="pricing-features">
                <?php foreach ($tier['features'] as $feature): ?>
                <li><?= $feature ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="/register?plan=<?= strtolower($tier['name']) ?>" class="pricing-cta <?= $tier['popular'] ? 'btn-primary' : 'btn-outline' ?>">
                <?= $tier['cta'] ?>
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="pricing-faq">
        <h2>Frequently Asked Questions</h2>
        <?php foreach ($faqs as $index => $faq): ?>
        <div class="faq-item" onclick="this.classList.toggle('open')">
            <div class="faq-question">
                <?= $faq['question'] ?>
                <span>▼</span>
            </div>
            <div class="faq-answer"><?= $faq['answer'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="pricing-cta-section">
        <p>Need a custom plan? <a href="/contact">Contact us</a> for enterprise solutions</p>
    </div>

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
                        <li><a href="/pricing">Pricing</a></li>
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