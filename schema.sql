-- SMM Panel Database Schema
-- MySQL 8.0+ / MariaDB 10.6+
-- Run this file to create all required tables

-- =====================================================
-- USERS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL COMMENT 'bcrypt hashed',
    `role` ENUM('customer', 'reseller', 'admin') NOT NULL DEFAULT 'customer',
    `balance` DECIMAL(12, 2) NOT NULL DEFAULT 0.00 COMMENT 'User wallet balance in USD',
    `api_key` VARCHAR(64) NULL UNIQUE COMMENT 'API key for reseller/API access',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Account active status',
    `last_login` DATETIME NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_email` (`email`),
    INDEX `idx_username` (`username`),
    INDEX `idx_role` (`role`),
    INDEX `idx_api_key` (`api_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='User accounts: customers, resellers, and admins';

-- =====================================================
-- SERVICES TABLE
-- Imported from Smmwiz API with local markup
-- =====================================================
CREATE TABLE IF NOT EXISTS `services` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `smmwiz_id` INT NOT NULL UNIQUE COMMENT 'Smmwiz service ID from API',
    `platform` ENUM('instagram', 'facebook', 'tiktok', 'youtube', 'twitter', 'linkedin', 'other') NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `type` VARCHAR(100) NOT NULL COMMENT 'e.g., followers, likes, views, comments',
    `category` VARCHAR(100) NOT NULL,
    `min_quantity` INT NOT NULL DEFAULT 1,
    `max_quantity` INT NOT NULL DEFAULT 100000,
    `rate` DECIMAL(10, 4) NOT NULL COMMENT 'Smmwiz base rate in USD',
    `our_rate` DECIMAL(10, 4) NOT NULL COMMENT 'Our rate with markup in USD',
    `markup_percentage` DECIMAL(5, 2) NOT NULL DEFAULT 0.00 COMMENT 'Markup applied e.g., 20.00 = 20%',
    `refill` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Does Smmwiz support refill?',
    `cancel` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Does Smmwiz support cancel?',
    `description` TEXT NULL COMMENT 'SEO-friendly description',
    `status` ENUM('active', 'inactive', 'hidden') NOT NULL DEFAULT 'active',
    `display_order` INT NOT NULL DEFAULT 0 COMMENT 'Order for display on services page',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `idx_smmwiz_id` (`smmwiz_id`),
    INDEX `idx_platform` (`platform`),
    INDEX `idx_type` (`type`),
    INDEX `idx_category` (`category`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='SMM services imported from Smmwiz with markup applied';

-- =====================================================
-- ORDERS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `orders` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `service_id` BIGINT UNSIGNED NOT NULL,
    `smmwiz_order_id` BIGINT UNSIGNED NULL COMMENT 'Order ID from Smmwiz API',
    `smmwiz_service_id` INT NOT NULL COMMENT 'Original Smmwiz service ID',
    `service_name` VARCHAR(255) NOT NULL COMMENT 'Service name at time of order',
    `link` TEXT NOT NULL COMMENT 'Target URL (profile, post, video)',
    `requested_quantity` INT NOT NULL,
    `delivered_quantity` INT NOT NULL DEFAULT 0,
    `start_count` INT NULL DEFAULT 0 COMMENT 'Starting count when order was placed',
    `remains` INT NULL COMMENT 'Remaining quantity from API',
    `status` ENUM('pending', 'in_progress', 'partial', 'completed', 'cancelled', 'refunded', 'refilled') NOT NULL DEFAULT 'pending',
    `charge_smw` DECIMAL(10, 4) NOT NULL COMMENT 'Charge from Smmwiz in USD',
    `our_charge` DECIMAL(10, 4) NOT NULL COMMENT 'Charge to customer in USD',
    `profit` DECIMAL(10, 4) NOT NULL DEFAULT 0.00 COMMENT 'our_charge - charge_smw',
    `refill_ids` JSON NULL COMMENT 'Array of Smmwiz refill IDs',
    `refill_count` INT NOT NULL DEFAULT 0 COMMENT 'Number of times refilled',
    `last_refill_at` DATETIME NULL,
    `api_response` JSON NULL COMMENT 'Full API response stored for debugging',
    `notes` TEXT NULL COMMENT 'Admin notes',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE RESTRICT,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_smmwiz_order_id` (`smmwiz_order_id`),
    INDEX `idx_service_id` (`service_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_created_at` (`created_at`),
    INDEX `idx_smmwiz_service_id` (`smmwiz_service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Customer orders synced with Smmwiz API';

-- =====================================================
-- LOGS TABLE
-- Action logging for debugging and audit
-- =====================================================
CREATE TABLE IF NOT EXISTS `logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL COMMENT 'Null for system actions',
    `action` VARCHAR(100) NOT NULL COMMENT 'e.g., order_created, balance_updated, refill_requested',
    `entity_type` VARCHAR(50) NULL COMMENT 'e.g., order, user, service',
    `entity_id` BIGINT UNSIGNED NULL COMMENT 'ID of affected entity',
    `payload` JSON NULL COMMENT 'Request/response data stored',
    `http_status` SMALLINT NULL COMMENT 'API response status code',
    `ip_address` VARCHAR(45) NULL COMMENT 'IPv4 or IPv6 address',
    `user_agent` VARCHAR(500) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_action` (`action`),
    INDEX `idx_entity` (`entity_type`, `entity_id`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Audit log for all user and system actions';

-- =====================================================
-- PAYMENTS TABLE (Future: UPI/Razorpay/PhonePe)
-- =====================================================
CREATE TABLE IF NOT EXISTS `payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `payment_id` VARCHAR(100) NULL UNIQUE COMMENT 'Gateway payment ID',
    `gateway` ENUM('stripe', 'razorpay', 'phonepe', 'upi', 'manual') NOT NULL DEFAULT 'manual',
    `amount` DECIMAL(12, 2) NOT NULL COMMENT 'Amount in USD',
    `currency` VARCHAR(3) NOT NULL DEFAULT 'USD',
    `status` ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    `payment_method` VARCHAR(50) NULL COMMENT 'Card last 4, UPI ID, etc.',
    `customer_email` VARCHAR(255) NULL,
    `metadata` JSON NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `completed_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_payment_id` (`payment_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_gateway` (`gateway`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Payment records for deposits';

-- =====================================================
-- TICKETS TABLE (Future: Support system)
-- =====================================================
CREATE TABLE IF NOT EXISTS `tickets` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `order_id` BIGINT UNSIGNED NULL,
    `subject` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('open', 'pending', 'resolved', 'closed') NOT NULL DEFAULT 'open',
    `priority` ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
    `assigned_to` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_order_id` (`order_id`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Support tickets';

-- =====================================================
-- INITIAL ADMIN USER
-- Default credentials: admin / admin123 (CHANGE IMMEDIATELY)
-- =====================================================
INSERT INTO `users` (`email`, `username`, `password`, `role`, `balance`, `is_active`)
VALUES ('admin@yourdomain.com', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 0.00, 1)
ON DUPLICATE KEY UPDATE `username` = `username`;

-- =====================================================
-- SAMPLE SERVICES (For initial testing)
-- These will be overwritten when syncing with Smmwiz API
-- =====================================================
INSERT INTO `services` (`smmwiz_id`, `platform`, `name`, `type`, `category`, `min_quantity`, `max_quantity`, `rate`, `our_rate`, `markup_percentage`, `refill`, `cancel`, `description`, `status`)
VALUES
(1, 'instagram', 'Instagram Followers', 'followers', 'Instagram', 10, 10000, 0.50, 0.60, 20.00, 1, 1, 'Get real Instagram followers instantly with 30-day refill guarantee', 'active'),
(2, 'instagram', 'Instagram Likes', 'likes', 'Instagram', 10, 50000, 0.10, 0.12, 20.00, 1, 1, 'Boost your Instagram post likes with fast delivery', 'active'),
(3, 'instagram', 'Instagram Views', 'views', 'Instagram', 100, 1000000, 0.05, 0.06, 20.00, 1, 0, 'Get more views on your Instagram videos and reels', 'active'),
(4, 'facebook', 'Facebook Page Likes', 'page_likes', 'Facebook', 100, 100000, 0.80, 0.96, 20.00, 1, 1, 'Increase your Facebook page likes organically', 'active'),
(5, 'tiktok', 'TikTok Followers', 'followers', 'TikTok', 100, 100000, 0.60, 0.72, 20.00, 1, 1, 'Grow your TikTok followers with real users', 'active'),
(6, 'tiktok', 'TikTok Likes', 'likes', 'TikTok', 100, 100000, 0.20, 0.24, 20.00, 1, 0, 'Get more likes on your TikTok videos', 'active'),
(7, 'youtube', 'YouTube Subscribers', 'subscribers', 'YouTube', 100, 100000, 1.50, 1.80, 20.00, 1, 1, 'Build your YouTube subscriber base with guaranteed delivery', 'active'),
(8, 'youtube', 'YouTube Views', 'views', 'YouTube', 100, 1000000, 0.30, 0.36, 20.00, 1, 0, 'Boost your YouTube video views for better reach', 'active')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- =====================================================
-- SCHEMA VERSION TRACKING
-- =====================================================
CREATE TABLE IF NOT EXISTS `schema_version` (
    `version` INT NOT NULL PRIMARY KEY,
    `applied_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `description` VARCHAR(255) NOT NULL
);

INSERT INTO `schema_version` (`version`, `description`) VALUES (1, 'Initial SMM panel schema')
ON DUPLICATE KEY UPDATE `version` = `version`;