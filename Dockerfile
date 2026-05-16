# SMM Panel - Simple PHP-FPM + Nginx
FROM php:8.3-fpm

# Install nginx only
RUN apt-get update && apt-get install -y \
    nginx \
    && rm -rf /var/lib/apt/lists/*

# Install PostgreSQL extensions
RUN docker-php-ext-install pdo pgsql

# Setup app
COPY nginx.conf /etc/nginx/nginx.conf
COPY . /var/www/html/
RUN mkdir -p /var/www/html/{assets,uploads,logs,tmp}
WORKDIR /var/www/html
EXPOSE 10000

COPY start.sh /start.sh
RUN chmod +x /start.sh
CMD ["/start.sh"]