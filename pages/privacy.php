<?php
/**
 * Privacy Policy Page - SEO Optimized
 *
 * @version 1.0.0
 */

$siteName = SITE_NAME;
$siteUrl = SITE_URL;
$pageTitle = "Privacy Policy | {$siteName}";
$pageDescription = "Read our privacy policy to understand how {$siteName} collects, uses, and protects your personal information when using our SMM panel services.";
?>
<!DOCTYPE html>
<html lang="en-IN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= $siteUrl ?>/privacy">
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
                    <li><a href="/terms">Terms</a></li>
                    <li><a href="/login" class="btn btn-outline">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="legal-page">
        <div class="container">
            <div class="page-header">
                <h1>Privacy Policy</h1>
                <p>Last updated: <?= date('F j, Y') ?></p>
            </div>

            <article class="legal-content">
                <section>
                    <h2>1. Information We Collect</h2>
                    <p>We collect information you provide directly to us:</p>
                    <ul>
                        <li><strong>Account Information:</strong> Email address, username, password (encrypted)</li>
                        <li><strong>Profile Information:</strong> Name, phone number</li>
                        <li><strong>Payment Information:</strong> Transaction history (we do not store full payment details)</li>
                        <li><strong>Order Information:</strong> Service requests, links to social media profiles</li>
                        <li><strong>Usage Data:</strong> IP address, browser type, access times</li>
                    </ul>
                </section>

                <section>
                    <h2>2. How We Use Your Information</h2>
                    <p>We use the collected information to:</p>
                    <ul>
                        <li>Provide and maintain our services</li>
                        <li>Process your transactions</li>
                        <li>Send you order updates and support messages</li>
                        <li>Improve and optimize our services</li>
                        <li>Comply with legal obligations</li>
                    </ul>
                </section>

                <section>
                    <h2>3. Information Sharing</h2>
                    <p>We do not sell, trade, or otherwise transfer your personal information to outside parties except:</p>
                    <ul>
                        <li><strong>Service Providers:</strong> Companies that help us operate our business (payment processors, hosting providers)</li>
                        <li><strong>Legal Requirements:</strong> When required by law or in response to valid requests</li>
                        <li><strong>Business Transfers:</strong> In connection with a merger or sale of company assets</li>
                    </ul>
                </section>

                <section>
                    <h2>4. Data Security</h2>
                    <p>We implement appropriate security measures to protect your personal information:</p>
                    <ul>
                        <li>SSL encryption for all data transmission</li>
                        <li>Secure database storage</li>
                        <li>Regular security audits</li>
                        <li>Access controls and authentication</li>
                    </ul>
                </section>

                <section>
                    <h2>5. Cookies and Tracking</h2>
                    <p>We use cookies to:</p>
                    <ul>
                        <li>Keep you logged in</li>
                        <li>Understand your preferences</li>
                        <li>Analyze site traffic</li>
                        <li>Improve user experience</li>
                    </ul>
                    <p>You can instruct your browser to refuse all cookies or indicate when a cookie is being sent.</p>
                </section>

                <section>
                    <h2>6. Third-Party Links</h2>
                    <p>Our website may contain links to third-party sites. We are not responsible for the privacy practices of those sites. We encourage you to read the privacy policies of any site you visit.</p>
                </section>

                <section>
                    <h2>7. Your Rights</h2>
                    <p>You have the right to:</p>
                    <ul>
                        <li>Access your personal information</li>
                        <li>Correct inaccurate data</li>
                        <li>Request deletion of your data</li>
                        <li>Opt-out of marketing communications</li>
                        <li>Export your data</li>
                    </ul>
                </section>

                <section>
                    <h2>8. Children's Privacy</h2>
                    <p>Our services are not intended for children under 18. We do not knowingly collect personal information from children. If we become aware of such collection, we will delete it immediately.</p>
                </section>

                <section>
                    <h2>9. Changes to Privacy Policy</h2>
                    <p>We may update this privacy policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the "Last updated" date.</p>
                </section>

                <section>
                    <h2>10. Contact Us</h2>
                    <p>If you have any questions about this Privacy Policy, please contact us at <?= SITE_EMAIL ?></p>
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