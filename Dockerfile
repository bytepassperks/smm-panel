# SMM Panel - PHP 8.2 + Nginx
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y nginx && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql

COPY nginx.conf /etc/nginx/nginx.conf
COPY . /var/www/html/
RUN mkdir -p /var/www/html/{assets,uploads,logs,tmp}
WORKDIR /var/www/html
EXPOSE 10000

COPY start.sh /start.sh
RUN chmod +x /start.sh
CMD ["/start.sh"]