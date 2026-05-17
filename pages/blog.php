<?php
/**
 * Blog Listing Page - SEO Optimized
 *
 * @version 1.0.0
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../components/svg_icons.php';

$siteName = SITE_NAME;
$siteUrl = SITE_URL;

// SEO Configuration
$pageTitle = "SMM Blog - Tips, Guides & Social Media Marketing News";
$pageDescription = "Latest articles on social media marketing, SMM panel guides, Instagram growth tips, and industry updates. Expert tips to grow your social media presence.";

// Sample blog posts (in production, fetch from database)
$posts = [
    [
        'slug' => 'how-to-get-1000-instagram-followers-in-2025',
        'title' => 'How to Get 1000 Instagram Followers in 2025',
        'excerpt' => 'Complete guide to growing your Instagram following organically in 2025. Learn the best strategies that actually work.',
        'category' => 'Instagram Growth',
        'date' => 'May 15, 2025',
        'read_time' => '8 min read',
        'image' => '📱'
    ],
    [
        'slug' => 'best-smm-panel-for-resellers',
        'title' => 'Best SMM Panel for Resellers in 2025',
        'excerpt' => 'Looking to start your own SMM reseller business? Here are the top SMM panels that offer the best wholesale rates and profit margins.',
        'category' => 'SMM Business',
        'date' => 'May 12, 2025',
        'read_time' => '6 min read',
        'image' => '💰'
    ],
    [
        'slug' => 'tiktok-views-buy-guide',
        'title' => 'Is It Safe to Buy TikTok Views? The Complete Guide',
        'excerpt' => 'Everything you need to know about buying TikTok views safely. We break down the risks, benefits, and best practices.',
        'category' => 'TikTok',
        'date' => 'May 10, 2025',
        'read_time' => '5 min read',
        'image' => '🎵'
    ],
    [
        'slug' => 'youtube-algorithm-2025',
        'title' => 'YouTube Algorithm Explained: What Works in 2025',
        'excerpt' => 'Understand how YouTube\'s algorithm works in 2025 and optimize your videos for maximum visibility and growth.',
        'category' => 'YouTube',
        'date' => 'May 8, 2025',
        'read_time' => '10 min read',
        'image' => '▶️'
    ],
    [
        'slug' => 'social-media-trends-2025',
        'title' => 'Top 10 Social Media Trends for 2025',
        'excerpt' => 'Stay ahead of the competition with these emerging social media trends that will shape the digital landscape in 2025.',
        'category' => 'Trends',
        'date' => 'May 5, 2025',
        'read_time' => '7 min read',
        'image' => '🔥'
    ],
    [
        'slug' => 'increase-engagement-instagram',
        'title' => 'Proven Ways to Increase Instagram Engagement Rate',
        'excerpt' => 'Learn how to boost your Instagram engagement with these expert tips and proven strategies used by top influencers.',
        'category' => 'Instagram Growth',
        'date' => 'May 3, 2025',
        'read_time' => '6 min read',
        'image' => '💬'
    ]
];

$categories = ['All', 'Instagram Growth', 'SMM Business', 'TikTok', 'YouTube', 'Trends'];
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
    <meta name="keywords" content="SMM blog, social media marketing tips, Instagram growth guide, SMM panel教程, digital marketing">
    <meta name="robots" content="index, follow">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= $siteUrl ?>/blog">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $siteUrl ?>/blog">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">

    <!-- JSON-LD Blog Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Blog",
        "name": "<?= htmlspecialchars($siteName) ?> Blog",
        "description": "<?= htmlspecialchars($pageDescription) ?>",
        "url": "<?= $siteUrl ?>/blog",
        "publisher": {
            "@type": "Organization",
            "name": "<?= htmlspecialchars($siteName) ?>"
        }
    }
    </script>

    <link rel="stylesheet" href="<?= $siteUrl ?>/assets/css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: var(--bg-primary); font-family: 'Inter', sans-serif; }
        .blog-hero {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            padding: 4rem 0;
            text-align: center;
            color: white;
        }
        .blog-hero h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        .blog-hero p {
            font-size: 1.125rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        .blog-filters {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
            padding: 2rem 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .blog-filter {
            padding: 8px 20px;
            border-radius: 50px;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        .blog-filter:hover, .blog-filter.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem 4rem;
        }
        .blog-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border-color: var(--primary);
        }
        .blog-card-image {
            height: 200px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
        }
        .blog-card-content {
            padding: 1.5rem;
        }
        .blog-card-category {
            display: inline-block;
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.75rem;
        }
        .blog-card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }
        .blog-card-excerpt {
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        .blog-card-meta {
            display: flex;
            justify-content: space-between;
            color: var(--text-muted);
            font-size: 0.8rem;
        }
        body.dark-mode .blog-card {
            background: #1e293b;
        }
        body.dark-mode .blog-filter {
            background: #1e293b;
        }
        body.dark-mode .blog-card-image {
            background: linear-gradient(135deg, #3730a3 0%, #4f46e5 100%);
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
                    <li><a href="/blog" class="active">Blog</a></li>
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

    <div class="blog-hero">
        <div class="container">
            <h1>SMM Blog</h1>
            <p>Expert tips, guides, and industry insights to help you grow your social media presence.</p>
        </div>
    </div>

    <div class="blog-filters">
        <?php foreach ($categories as $cat): ?>
        <a href="/blog?category=<?= urlencode($cat) ?>" class="blog-filter <?= $cat === 'All' ? 'active' : '' ?>">
            <?= $cat ?>
        </a>
        <?php endforeach; ?>
    </div>

    <div class="blog-grid">
        <?php foreach ($posts as $post): ?>
        <a href="/blog/<?= $post['slug'] ?>" class="blog-card">
            <div class="blog-card-image"><?= $post['image'] ?></div>
            <div class="blog-card-content">
                <span class="blog-card-category"><?= $post['category'] ?></span>
                <h3 class="blog-card-title"><?= $post['title'] ?></h3>
                <p class="blog-card-excerpt"><?= $post['excerpt'] ?></p>
                <div class="blog-card-meta">
                    <span><?= $post['date'] ?></span>
                    <span><?= $post['read_time'] ?></span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
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