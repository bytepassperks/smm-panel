<?php
/**
 * Sample Blog Post Page
 *
 * In production, this would be routed via slug parameter
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../components/svg_icons.php';

$siteName = SITE_NAME;
$siteUrl = SITE_URL;

// Sample post data (in production, fetch from database)
$post = [
    'title' => 'How to Get 1000 Instagram Followers in 2025',
    'category' => 'Instagram Growth',
    'date' => 'May 15, 2025',
    'read_time' => '8 min read',
    'author' => 'SMM Expert',
    'content' => '
        <p>Growing your Instagram following to 1000 followers is a significant milestone for any content creator or business. In this comprehensive guide, we will explore proven strategies that actually work in 2025.</p>

        <h2>Understanding the Instagram Algorithm in 2025</h2>
        <p>The Instagram algorithm has evolved significantly. It now prioritizes content that drives meaningful interactions. The key factors are:</p>
        <ul>
            <li><strong>Engagement Rate:</strong> Posts that receive quick engagement rank higher</li>
            <li><strong>Save & Share:</strong> Content that people save or share gets boosted</li>
            <li><strong>Time Spent:</strong> Reels that keep viewers watching longer get more reach</li>
            <li><strong>Account Consistency:</strong> Regular posting helps maintain visibility</li>
        </ul>

        <h2>Strategy 1: Optimize Your Profile</h2>
        <p>Your Instagram profile is your first impression. Make it count:</p>
        <ul>
            <li>Use a clear profile picture (logo or professional photo)</li>
            <li>Write a compelling bio that explains what you offer</li>
            <li>Include relevant keywords in your username and bio</li>
            <li>Add a link to your website or Linktree</li>
        </ul>

        <h2>Strategy 2: Create Quality Content</h2>
        <p>Content is king on Instagram. Focus on:</p>
        <ul>
            <li>High-quality images with good lighting</li>
            <li>Engaging Reels that provide value or entertainment</li>
            <li>Consistent visual aesthetic that reflects your brand</li>
            <li>Captions that encourage engagement (questions, CTAs)</li>
        </ul>

        <h2>Strategy 3: Leverage Hashtags Strategically</h2>
        <p>Use a mix of popular and niche hashtags:</p>
        <ul>
            <li>5-10 high-competition hashtags (e.g., #instagood)</li>
            <li>10-15 medium-competition hashtags</li>
            <li>5-10 niche-specific hashtags (less competition)</li>
            <li>Create a branded hashtag for your account</li>
        </ul>

        <h2>Strategy 4: Engage with Your Audience</h2>
        <p>Building a community is crucial:</p>
        <ul>
            <li>Reply to all comments within the first hour</li>
            <li>Engage with other accounts in your niche</li>
            <li>Use Instagram Stories to interact with followers</li>
            <li>Host giveaways and collaborations</li>
        </ul>

        <h2>Strategy 5: Use SMM Services Strategically</h2>
        <p>SMM panels can give your account an initial boost:</p>
        <ul>
            <li>Start with small packages (100-500 followers)</li>
            <li>Focus on engagement services (likes, comments)</li>
            <li>Choose services with refill guarantee</li>
            <li>Combine with organic growth strategies</li>
        </ul>

        <h2>Conclusion</h2>
        <p>Getting to 1000 followers requires a combination of quality content, strategic engagement, and patience. By implementing these strategies consistently, you should see significant growth within 2-3 months.</p>

        <p>Remember: Quality always beats quantity. Focus on building an engaged community rather than just chasing numbers.</p>
    '
];

// SEO
$pageTitle = $post['title'] . " | " . $siteName . " Blog";
$pageDescription = "Learn how to get 1000 Instagram followers in 2025 with our comprehensive guide. Proven strategies that actually work for organic growth.";
?>
<!DOCTYPE html>
<html lang="en-IN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="robots" content="index, follow">

    <link rel="canonical" href="<?= $siteUrl ?>/blog/how-to-get-1000-instagram-followers-in-2025">

    <meta property="og:type" content="article">
    <meta property="og:url" content="<?= $siteUrl ?>/blog/how-to-get-1000-instagram-followers-in-2025">
    <meta property="og:title" content="<?= htmlspecialchars($post['title']) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta property="article:published_time" content="2025-05-15T10:00:00+05:30">
    <meta property="article:author" content="<?= $post['author'] ?>">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": "<?= htmlspecialchars($post['title']) ?>",
        "description": "<?= htmlspecialchars($pageDescription) ?>",
        "author": {
            "@type": "Person",
            "name": "<?= $post['author'] ?>"
        },
        "publisher": {
            "@type": "Organization",
            "name": "<?= htmlspecialchars($siteName) ?>"
        },
        "datePublished": "2025-05-15",
        "dateModified": "2025-05-15"
    }
    </script>

    <link rel="stylesheet" href="<?= $siteUrl ?>/assets/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: var(--bg-primary); font-family: 'Inter', sans-serif; }
        .post-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 3rem 0;
            text-align: center;
            color: white;
        }
        .post-hero h1 {
            font-size: 2.25rem;
            font-weight: 800;
            max-width: 800px;
            margin: 0 auto 1.5rem;
            line-height: 1.3;
        }
        .post-meta {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .post-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
        }
        .post-content h2 {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 700;
            margin: 2rem 0 1rem;
        }
        .post-content p {
            color: var(--text-secondary);
            font-size: 1.05rem;
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }
        .post-content ul {
            margin: 1.5rem 0;
            padding-left: 1.5rem;
        }
        .post-content li {
            color: var(--text-secondary);
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 0.75rem;
        }
        .post-content li strong {
            color: var(--text-primary);
        }
        .post-category {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-bottom: 1rem;
        }
        .related-posts {
            background: var(--bg-secondary);
            padding: 3rem 0;
        }
        .related-posts h2 {
            text-align: center;
            color: var(--text-primary);
            margin-bottom: 2rem;
        }
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        .related-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
        }
        .related-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
        }
        .related-card h3 {
            color: var(--text-primary);
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        .related-card p {
            color: var(--text-secondary);
            font-size: 0.85rem;
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
                <button class="hamburger" aria-label="Menu"><span></span><span></span><span></span></button>
            </nav>
        </div>
    </header>

    <div class="post-hero">
        <div class="container">
            <span class="post-category"><?= $post['category'] ?></span>
            <h1><?= $post['title'] ?></h1>
            <div class="post-meta">
                <span>📅 <?= $post['date'] ?></span>
                <span>⏱️ <?= $post['read_time'] ?></span>
                <span>👤 <?= $post['author'] ?></span>
            </div>
        </div>
    </div>

    <article class="post-content">
        <?= $post['content'] ?>
    </article>

    <section class="related-posts">
        <div class="container">
            <h2>Related Articles</h2>
            <div class="related-grid">
                <a href="/blog/increase-engagement-instagram" class="related-card">
                    <h3>Proven Ways to Increase Instagram Engagement Rate</h3>
                    <p>Learn how to boost your Instagram engagement...</p>
                </a>
                <a href="/blog/social-media-trends-2025" class="related-card">
                    <h3>Top 10 Social Media Trends for 2025</h3>
                    <p>Stay ahead of the competition with emerging trends...</p>
                </a>
                <a href="/blog/best-smm-panel-for-resellers" class="related-card">
                    <h3>Best SMM Panel for Resellers in 2025</h3>
                    <p>Top SMM panels for wholesale rates and profits...</p>
                </a>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4><?= htmlspecialchars($siteName) ?></h4>
                    <p>Your trusted SMM panel for all social media services.</p>
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