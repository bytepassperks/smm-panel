<?php
/**
 * Terms of Service Page - SEO Optimized
 *
 * @version 1.0.0
 */

$siteName = SITE_NAME;
$siteUrl = SITE_URL;
$pageTitle = "Terms of Service | {$siteName}";
$pageDescription = "Read our terms of service for using the {$siteName} SMM panel. Understand our policies, user responsibilities, and service agreements.";
?>
<!DOCTYPE html>
<html lang="en-IN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= $siteUrl ?>/terms">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
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
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/terms" class="active">Terms</a></li>
                    <li><a href="/login" class="btn btn-outline">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="legal-page">
        <div class="container">
            <div class="page-header">
                <h1>Terms of Service</h1>
                <p>Last updated: <?= date('F j, Y') ?></p>
            </div>

            <article class="legal-content">
                <section>
                    <h2>1. Acceptance of Terms</h2>
                    <p>By accessing and using <?= htmlspecialchars($siteName) ?> ("we", "our", or "us"), you accept and agree to be bound by the terms and provisions of this agreement. If you do not agree to abide by these terms, please do not use this service.</p>
                </section>

                <section>
                    <h2>2. Description of Service</h2>
                    <p><?= htmlspecialchars($siteName) ?> provides social media marketing (SMM) services including but not limited to:</p>
                    <ul>
                        <li>Instagram followers, likes, views, comments</li>
                        <li>Facebook page likes, followers, video views</li>
                        <li>TikTok followers, likes, views</li>
                        <li>YouTube subscribers, views, likes</li>
                        <li>Other social media platform services</li>
                    </ul>
                </section>

                <section>
                    <h2>3. User Accounts and Eligibility</h2>
                    <p>To use our services, you must:</p>
                    <ul>
                        <li>Be at least 18 years of age</li>
                        <li>Provide accurate and complete registration information</li>
                        <li>Maintain the security of your account</li>
                        <li>Be responsible for all activities under your account</li>
                    </ul>
                </section>

                <section>
                    <h2>4. Orders and Payments</h2>
                    <p>All orders are subject to the following terms:</p>
                    <ul>
                        <li>Prices are subject to change without notice</li>
                        <li>Payment must be received before order processing begins</li>
                        <li>Orders are processed based on availability</li>
                        <li>Refunds are available only for failed orders</li>
                    </ul>
                </section>

                <section>
                    <h2>5. Delivery and Refill Policy</h2>
                    <p>We provide the following delivery guarantees:</p>
                    <ul>
                        <li>Most orders start within 1-24 hours</li>
                        <li>30-day refill guarantee on most services</li>
                        <li>Refill applies only to drops, not to new orders</li>
                        <li>Refill subject to service availability</li>
                    </ul>
                </section>

                <section>
                    <h2>6. Prohibited Uses</h2>
                    <p>You may not use our services to:</p>
                    <ul>
                        <li>Violate any laws or regulations</li>
                        <li>Infringe on intellectual property rights</li>
                        <li>Engage in fraudulent activities</li>
                        <li>Spam or harass others</li>
                        <li>Attempt to hack or compromise our systems</li>
                    </ul>
                </section>

                <section>
                    <h2>7. Limitation of Liability</h2>
                    <p><?= htmlspecialchars($siteName) ?> shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of our services.</p>
                </section>

                <section>
                    <h2>8. Changes to Terms</h2>
                    <p>We reserve the right to modify these terms at any time. Your continued use of our services after any changes indicates your acceptance of the new terms.</p>
                </section>

                <section>
                    <h2>9. Contact Information</h2>
                    <p>If you have any questions about these Terms of Service, please contact us at <?= SITE_EMAIL ?></p>
                </section>
            </article>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($siteName) ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>