<?php
/**
 * FAQ Page - SEO Optimized with FAQPage Schema
 *
 * @version 1.0.0
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';

$siteName = SITE_NAME;
$siteUrl = SITE_URL;
$pageTitle = "FAQ - Frequently Asked Questions | {$siteName}";
$pageDescription = "Find answers to common questions about our SMM panel services. Learn about delivery times, refill policy, payment methods, and more.";

// FAQ Schema
$faqSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => [
        [
            '@type' => 'Question',
            'name' => 'What is an SMM panel?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'An SMM (Social Media Marketing) panel is an online platform that provides social media services like followers, likes, views, comments, and more. It connects you with service providers who can boost your social media presence on platforms like Instagram, Facebook, TikTok, and YouTube.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'Is it safe to buy followers and likes?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Yes, our services are designed to be safe and comply with social media platform terms of service. We provide high-quality, real-like engagement that helps grow your social media presence organically without risking your account.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'How long does delivery take?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Delivery times vary by service and quantity. Small orders typically start within 1-30 minutes. Large orders may take 1-24 hours for initial delivery and up to 5 days for completion. You can track your order status in your dashboard.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'Do you offer refill guarantee?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Yes! Most of our services come with a 30-day refill guarantee. If your followers or likes drop within 30 days of delivery, we will refill them at no additional cost. Refills are subject to service availability.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'What payment methods do you accept?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'We accept multiple payment methods including UPI (India), Credit/Debit Cards, Paytm, and Bank Transfers. More payment options coming soon including Razorpay and PhonePe.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'Can I cancel my order?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Orders can only be cancelled if they are still in "Pending" status. Once the order moves to "In Progress" or "Completed", it cannot be cancelled. Please check the service details - some services support cancellation while in progress.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'How do I track my order?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'After placing an order, you can track its status in your dashboard. You can also check the status using the Order Status tool on our website. Orders sync automatically every few minutes with our provider.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'What if my order fails?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'If your order fails, contact our support team immediately. We will investigate the issue and either provide a refund or attempt the order again. Most failed orders are resolved within 24-48 hours.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'Do you provide support?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Yes, we offer 24/7 customer support. You can reach us through email, WhatsApp, or our support ticket system. Our team is always ready to help you with any questions or concerns.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'Are there any discounts for bulk orders?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Yes! We offer special discounts for bulk orders. Contact our team for custom quotes on large quantities. Resellers also get special wholesale pricing - sign up as a reseller to access exclusive rates.'
            ]
        ]
    ]
];

// Category-specific FAQs
$faqsByCategory = [
    'general' => [
        ['q' => 'What is an SMM panel?', 'a' => 'An SMM (Social Media Marketing) panel is an online platform that provides social media services like followers, likes, views, comments, and more.'],
        ['q' => 'Is it safe to buy followers and likes?', 'a' => 'Yes, our services are designed to be safe and comply with social media platform terms of service.'],
        ['q' => 'Do you provide support?', 'a' => 'Yes, we offer 24/7 customer support through email, WhatsApp, and our support ticket system.'],
    ],
    'delivery' => [
        ['q' => 'How long does delivery take?', 'a' => 'Delivery times vary by service and quantity. Small orders typically start within 1-30 minutes. Large orders may take 1-24 hours for initial delivery and up to 5 days for completion.'],
        ['q' => 'How do I track my order?', 'a' => 'After placing an order, you can track its status in your dashboard. Orders sync automatically every few minutes with our provider.'],
    ],
    'refill' => [
        ['q' => 'Do you offer refill guarantee?', 'a' => 'Yes! Most of our services come with a 30-day refill guarantee. If your followers or likes drop within 30 days of delivery, we will refill them at no additional cost.'],
        ['q' => 'What is the refill policy?', 'a' => 'Refills are subject to service availability. The service must still be active on our provider. Refill requests must be made within 30 days of delivery.'],
    ],
    'payment' => [
        ['q' => 'What payment methods do you accept?', 'a' => 'We accept UPI (India), Credit/Debit Cards, Paytm, and Bank Transfers.'],
        ['q' => 'How do I add funds to my account?', 'a' => 'Go to your dashboard and click "Add Funds". Select your preferred payment method and enter the amount. Your balance will be updated automatically after successful payment.'],
    ],
    'orders' => [
        ['q' => 'Can I cancel my order?', 'a' => 'Orders can only be cancelled if they are still in "Pending" status. Once the order moves to "In Progress" or "Completed", it cannot be cancelled.'],
        ['q' => 'What if my order fails?', 'a' => 'If your order fails, contact our support team immediately. We will investigate the issue and either provide a refund or attempt the order again.'],
    ]
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
    <meta name="keywords" content="FAQ, SMM panel FAQ, buy followers FAQ, SMM questions, social media marketing FAQ">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= $siteUrl ?>/faq">

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $siteUrl ?>/faq">
    <meta property="og:title" content="FAQ - Frequently Asked Questions | <?= $siteName ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">

    <!-- FAQPage Schema -->
    <?= jsonLd($faqSchema) ?>

    <link rel="stylesheet" href="<?= $siteUrl ?>/assets/css/index.css">
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
                    <li><a href="/">Home</a></li>
                    <li><a href="/services">Services</a></li>
                    <li><a href="/faq" class="active">FAQ</a></li>
                    <li><a href="/terms">Terms</a></li>
                    <li><a href="/login" class="btn btn-outline">Login</a></li>
                    <li><a href="/register" class="btn btn-primary">Sign Up</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="faq-page">
        <div class="container">
            <div class="page-header">
                <h1>Frequently Asked Questions</h1>
                <p>Find answers to common questions about our SMM panel services</p>
            </div>

            <!-- Search -->
            <div class="faq-search">
                <input type="text" id="faqSearch" placeholder="Search for answers...">
            </div>

            <!-- FAQ Categories -->
            <div class="faq-categories">
                <button class="faq-tab active" data-category="all">All</button>
                <button class="faq-tab" data-category="general">General</button>
                <button class="faq-tab" data-category="delivery">Delivery</button>
                <button class="faq-tab" data-category="refill">Refill Policy</button>
                <button class="faq-tab" data-category="payment">Payment</button>
                <button class="faq-tab" data-category="orders">Orders</button>
            </div>

            <!-- FAQ List -->
            <div class="faq-list">
                <?php foreach ($faqsByCategory as $category => $faqs): ?>
                    <?php foreach ($faqs as $index => $faq): ?>
                    <details class="faq-item" data-category="<?= $category ?>">
                        <summary>
                            <?= htmlspecialchars($faq['q']) ?>
                            <span class="expand-icon">+</span>
                        </summary>
                        <div class="faq-answer">
                            <p><?= htmlspecialchars($faq['a']) ?></p>
                        </div>
                    </details>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>

            <!-- Still Have Questions -->
            <div class="contact-cta">
                <h2>Still Have Questions?</h2>
                <p>Can't find the answer you're looking for? Contact our support team.</p>
                <a href="/contact" class="btn btn-primary">Contact Support</a>
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

    <script>
        // FAQ Tab functionality
        document.querySelectorAll('.faq-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.faq-tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const category = tab.dataset.category;
                document.querySelectorAll('.faq-item').forEach(item => {
                    if (category === 'all' || item.dataset.category === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // Search functionality
        document.getElementById('faqSearch').addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.faq-item').forEach(item => {
                const question = item.querySelector('summary').textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>