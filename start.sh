#!/bin/bash
set -e

# Set environment variables
export DB_HOST=10.60.139.53
export DB_PORT=3306
export DB_NAME=h6i63l3c0u
export DB_USER=h6i63l3c0u
export DB_PASS=8-Jos-!4xZ2p
export SMMWIZ_API_KEY=590d78f5f73a1e4a5816fa7c997e0bf6
export SMMWIZ_API_URL=https://smmwiz.com/api/v2
export SITE_NAME="SMM Panel"
export SITE_URL=https://smm-panel-5lqp.onrender.com
export APP_ENV=production
export DEFAULT_MARKUP=20.00

echo "Environment variables set"

# Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm &

# Give PHP-FPM a moment to start
sleep 2

# Start Nginx in foreground
echo "Starting Nginx..."
nginx -g 'daemon off;'