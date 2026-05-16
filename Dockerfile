# SMM Panel - Docker Production Image
# PHP 8.3-FPM + Nginx

FROM php:8.3-fpm-bookworm

# Install nginx and dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    && rm -rf /var/lib/apt/lists/*

# PHP configuration
RUN docker-php-ext-install pdo pdo_mysql

# Copy nginx config
COPY nginx.conf /etc/nginx/nginx.conf

# Copy app
COPY . /var/www/html/
RUN mkdir -p /var/www/html/{assets/{css,js,images},uploads,logs,tmp}

# Set working directory
WORKDIR /var/www/html

# Expose port
EXPOSE 10000

# Start PHP-FPM and Nginx
CMD bash -c "php-fpm & nginx -g 'daemon off;'"