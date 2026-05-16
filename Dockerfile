# SMM Panel - Docker Production Image
# PHP 8.3-FPM + Nginx + Supervisor
#
# Build: docker build -t smm-panel .
# Run: docker run -p 10000:10000 smm-panel

# =====================================================
# Base Image
# =====================================================
FROM php:8.3-fpm-bookworm

# =====================================================
# System Dependencies
# =====================================================
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    curl \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    && rm -rf /var/lib/apt/lists/*

# =====================================================
# PHP Extensions
# =====================================================
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        zip \
        gd \
        mbstring \
        curl \
        json \
        xml \
        intl \
        bcmath \
    && docker-php-ext-enable pdo_mysql

# =====================================================
# Composer (for future dependencies)
# =====================================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =====================================================
# Nginx Configuration
# =====================================================
COPY nginx.conf /etc/nginx/nginx.conf

# =====================================================
# Supervisor Configuration
# =====================================================
RUN mkdir -p /var/log/supervisor
COPY supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# =====================================================
# Application Files
# =====================================================
WORKDIR /var/www/html

# Copy all application files
COPY --chown=www-data:www-data . /var/www/html/

# Create necessary directories
RUN mkdir -p /var/www/html/{assets/{css,js,images},uploads,logs,tmp} \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/{uploads,logs,tmp}

# =====================================================
# Environment Variables
# =====================================================
ENV APP_ENV=production
ENV PHP_MEMORY_LIMIT=256M
ENV PHP_MAX_EXECUTION_TIME=300
ENV PHP_UPLOAD_MAX_FILESIZE=20M
ENV PHP_POST_MAX_SIZE=25M

# =====================================================
# Expose Port (Render expects 10000)
# =====================================================
EXPOSE 10000

# =====================================================
# Start Script
# =====================================================
COPY start.sh /start.sh
RUN chmod +x /start.sh

# =====================================================
# Start Services
# =====================================================
CMD ["/start.sh"]