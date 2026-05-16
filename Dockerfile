# SMM Panel - Docker Production Image
# PHP 8.3-FPM + Nginx

FROM php:8.3-fpm-bookworm

# Install nginx and dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    && rm -rf /var/lib/apt/lists/*

# PHP configuration - PostgreSQL
RUN docker-php-ext-install pdo pgsql

# Copy nginx config
COPY nginx.conf /etc/nginx/nginx.conf

# Copy app
COPY . /var/www/html/
RUN mkdir -p /var/www/html/{assets/{css,js,images},uploads,logs,tmp}

# Set working directory
WORKDIR /var/www/html

# Expose port
EXPOSE 10000

# Copy and set start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Start with environment variables
CMD ["/start.sh"]