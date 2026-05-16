# SMM Panel - Docker Production Image
# PHP 8.3-FPM + Nginx

FROM php:8.3-fpm-slim

# Install nginx and required packages
RUN apt-get update && apt-get install -y \
    nginx \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Copy nginx config
COPY nginx.conf /etc/nginx/nginx.conf

# Copy app
COPY . /var/www/html/
RUN mkdir -p /var/www/html/{assets/{css,js,images},uploads,logs,tmp}

WORKDIR /var/www/html

EXPOSE 10000

COPY start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]