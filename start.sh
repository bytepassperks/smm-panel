#!/bin/bash
set -e

# Wait for any initialization
sleep 1

# Start PHP-FPM in background
echo "Starting PHP-FPM..."
php-fpm --nodaemonize &

# Give PHP-FPM a moment to start
sleep 2

# Start Nginx in foreground
echo "Starting Nginx..."
nginx -g 'daemon off;'