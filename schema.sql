-- SMM Panel Database Schema - PostgreSQL
-- Run this file to create all required tables

-- =====================================================
-- USERS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id BIGSERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'customer' CHECK (role IN ('customer', 'reseller', 'admin')),
    balance DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    api_key VARCHAR(64) UNIQUE,
    is_active BOOLEAN NOT NULL DEFAULT true,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_api_key ON users(api_key);

-- =====================================================
-- SERVICES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS services (
    id BIGSERIAL PRIMARY KEY,
    smmwiz_id INTEGER NOT NULL UNIQUE,
    platform VARCHAR(20) NOT NULL CHECK (platform IN ('instagram', 'facebook', 'tiktok', 'youtube', 'twitter', 'linkedin', 'other')),
    name VARCHAR(255) NOT NULL,
    type VARCHAR(100) NOT NULL,
    category VARCHAR(100) NOT NULL,
    min_quantity INTEGER NOT NULL DEFAULT 1,
    max_quantity INTEGER NOT NULL DEFAULT 100000,
    rate DECIMAL(10, 4) NOT NULL,
    our_rate DECIMAL(10, 4) NOT NULL,
    markup_percentage DECIMAL(5, 2) NOT NULL DEFAULT 0.00,
    refill BOOLEAN NOT NULL DEFAULT false,
    cancel BOOLEAN NOT NULL DEFAULT false,
    description TEXT,
    status VARCHAR(20) NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'inactive', 'hidden')),
    display_order INTEGER NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_services_smmwiz_id ON services(smmwiz_id);
CREATE INDEX idx_services_platform ON services(platform);
CREATE INDEX idx_services_type ON services(type);
CREATE INDEX idx_services_category ON services(category);
CREATE INDEX idx_services_status ON services(status);

-- =====================================================
-- ORDERS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS orders (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    service_id BIGINT NOT NULL REFERENCES services(id) ON DELETE RESTRICT,
    smmwiz_order_id BIGINT,
    smmwiz_service_id INTEGER NOT NULL,
    service_name VARCHAR(255) NOT NULL,
    link TEXT NOT NULL,
    requested_quantity INTEGER NOT NULL,
    delivered_quantity INTEGER NOT NULL DEFAULT 0,
    start_count INTEGER DEFAULT 0,
    remains INTEGER,
    status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'in_progress', 'partial', 'completed', 'cancelled', 'refunded', 'refilled')),
    charge_smw DECIMAL(10, 4) NOT NULL,
    our_charge DECIMAL(10, 4) NOT NULL,
    profit DECIMAL(10, 4) NOT NULL DEFAULT 0.00,
    refill_ids JSONB,
    refill_count INTEGER NOT NULL DEFAULT 0,
    last_refill_at TIMESTAMP,
    api_response JSONB,
    notes TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_orders_smmwiz_order_id ON orders(smmwiz_order_id);
CREATE INDEX idx_orders_service_id ON orders(service_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_created_at ON orders(created_at);
CREATE INDEX idx_orders_smmwiz_service_id ON orders(smmwiz_service_id);

-- =====================================================
-- LOGS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS logs (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE SET NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50),
    entity_id BIGINT,
    payload JSONB,
    http_status SMALLINT,
    ip_address VARCHAR(45),
    user_agent VARCHAR(500),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_logs_user_id ON logs(user_id);
CREATE INDEX idx_logs_action ON logs(action);
CREATE INDEX idx_logs_entity ON logs(entity_type, entity_id);
CREATE INDEX idx_logs_created_at ON logs(created_at);

-- =====================================================
-- PAYMENTS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS payments (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    payment_id VARCHAR(100) UNIQUE,
    gateway VARCHAR(20) NOT NULL DEFAULT 'manual' CHECK (gateway IN ('stripe', 'razorpay', 'phonepe', 'upi', 'manual')),
    amount DECIMAL(12, 2) NOT NULL,
    currency VARCHAR(3) NOT NULL DEFAULT 'USD',
    status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'completed', 'failed', 'refunded')),
    payment_method VARCHAR(50),
    customer_email VARCHAR(255),
    metadata JSONB,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP
);

CREATE INDEX idx_payments_user_id ON payments(user_id);
CREATE INDEX idx_payments_payment_id ON payments(payment_id);
CREATE INDEX idx_payments_status ON payments(status);
CREATE INDEX idx_payments_gateway ON payments(gateway);

-- =====================================================
-- TICKETS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS tickets (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    order_id BIGINT REFERENCES orders(id) ON DELETE SET NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'open' CHECK (status IN ('open', 'pending', 'resolved', 'closed')),
    priority VARCHAR(20) NOT NULL DEFAULT 'medium' CHECK (priority IN ('low', 'medium', 'high')),
    assigned_to BIGINT REFERENCES users(id) ON DELETE SET NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_tickets_user_id ON tickets(user_id);
CREATE INDEX idx_tickets_order_id ON tickets(order_id);
CREATE INDEX idx_tickets_status ON tickets(status);

-- =====================================================
-- SCHEMA VERSION
-- =====================================================
CREATE TABLE IF NOT EXISTS schema_version (
    version INTEGER NOT NULL PRIMARY KEY,
    applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    description VARCHAR(255) NOT NULL
);

-- =====================================================
-- ADMIN USER
-- Default password: admin123 (hash)
-- =====================================================
INSERT INTO users (email, username, password, role, balance, is_active)
VALUES ('admin@yourdomain.com', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 0.00, true)
ON CONFLICT (username) DO NOTHING;

-- =====================================================
-- SAMPLE SERVICES
-- =====================================================
INSERT INTO services (smmwiz_id, platform, name, type, category, min_quantity, max_quantity, rate, our_rate, markup_percentage, refill, cancel, description, status)
VALUES
(1, 'instagram', 'Instagram Followers', 'followers', 'Instagram', 10, 10000, 0.50, 0.60, 20.00, true, true, 'Get real Instagram followers instantly with 30-day refill guarantee', 'active'),
(2, 'instagram', 'Instagram Likes', 'likes', 'Instagram', 10, 50000, 0.10, 0.12, 20.00, true, true, 'Boost your Instagram post likes with fast delivery', 'active'),
(3, 'instagram', 'Instagram Views', 'views', 'Instagram', 100, 1000000, 0.05, 0.06, 20.00, true, false, 'Get more views on your Instagram videos and reels', 'active'),
(4, 'facebook', 'Facebook Page Likes', 'page_likes', 'Facebook', 100, 100000, 0.80, 0.96, 20.00, true, true, 'Increase your Facebook page likes organically', 'active'),
(5, 'tiktok', 'TikTok Followers', 'followers', 'TikTok', 100, 100000, 0.60, 0.72, 20.00, true, true, 'Grow your TikTok followers with real users', 'active'),
(6, 'tiktok', 'TikTok Likes', 'likes', 'TikTok', 100, 100000, 0.20, 0.24, 20.00, true, false, 'Get more likes on your TikTok videos', 'active'),
(7, 'youtube', 'YouTube Subscribers', 'subscribers', 'YouTube', 100, 100000, 1.50, 1.80, 20.00, true, true, 'Build your YouTube subscriber base with guaranteed delivery', 'active'),
(8, 'youtube', 'YouTube Views', 'views', 'YouTube', 100, 1000000, 0.30, 0.36, 20.00, true, false, 'Boost your YouTube video views for better reach', 'active')
ON CONFLICT (smmwiz_id) DO NOTHING;

-- =====================================================
-- SCHEMA VERSION
-- =====================================================
INSERT INTO schema_version (version, description) VALUES (1, 'Initial SMM panel schema - PostgreSQL')
ON CONFLICT (version) DO NOTHING;