# SMM Panel
FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    php8.3-pgsql \
    && rm -rf /var/lib/apt/lists/*

COPY nginx.conf /etc/nginx/nginx.conf
COPY . /var/www/html/
RUN mkdir -p /var/www/html/{assets,uploads,logs,tmp}
WORKDIR /var/www/html
EXPOSE 10000

COPY start.sh /start.sh
RUN chmod +x /start.sh
CMD ["/start.sh"]