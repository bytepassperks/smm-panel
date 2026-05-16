#!/bin/bash
set -e

# PostgreSQL Configuration
export DB_HOST=dpg-d845ip8jo89c73ai6ptg-a.oregon-postgres.render.com
export DB_PORT=5432
export DB_NAME=smmpanel_jyot
export DB_USER=smmpanel_jyot_user
export DB_PASS=mJ6r2uHfc0ayaaRbFVdYBKuePpUViYFF

# SMMWIZ API
export SMMWIZ_API_KEY=590d78f5f73a1e4a5816fa7c997e0bf6
export SMMWIZ_API_URL=https://smmwiz.com/api/v2

# Site Config
export SITE_NAME="SMM Panel"
export SITE_URL=https://smm-panel-5lqp.onrender.com
export APP_ENV=production
export DEFAULT_MARKUP=20.00

# Create .env file for PHP
cd /var/www/html
cat > .env << 'ENVEOF'
DB_HOST=dpg-d845ip8jo89c73ai6ptg-a.oregon-postgres.render.com
DB_PORT=5432
DB_NAME=smmpanel_jyot
DB_USER=smmpanel_jyot_user
DB_PASS=mJ6r2uHfc0ayaaRbFVdYBKuePpUViYFF
SMMWIZ_API_KEY=590d78f5f73a1e4a5816fa7c997e0bf6
SMMWIZ_API_URL=https://smmwiz.com/api/v2
SITE_NAME=SMM Panel
SITE_URL=https://smm-panel-5lqp.onrender.com
APP_ENV=production
DEFAULT_MARKUP=20.00
ENVEOF

echo "Starting SMM Panel..."
echo "DB Host: $DB_HOST"
echo "DB Name: $DB_NAME"

# Start PHP-FPM
php-fpm &

# Give PHP-FPM a moment to start
sleep 2

# Start Nginx in foreground
nginx -g 'daemon off;'