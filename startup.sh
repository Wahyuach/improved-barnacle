#!/bin/bash

# Azure Web App Startup Script for Laravel with Nginx
set -e

# 1. Install PHP extensions if needed
echo "Checking PHP extensions..."
php -m | grep -q mbstring || echo "mbstring not found"

# 2. Navigate to application root
cd /home/site/wwwroot

# 3. Install Composer dependencies
echo "Installing Composer dependencies..."
if [ ! -d "vendor" ]; then
    composer install --no-dev --optimize-autoloader
fi

# 4. Create .env file if it doesn't exist
if [ ! -f ".env" ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env
fi

# 5. Generate application key if not set
echo "Ensuring APP_KEY is set..."
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate
fi

# 6. Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# 7. Clear and cache Laravel configuration
echo "Optimizing Laravel configuration..."
php artisan config:cache
php artisan route:cache

# 8. Install npm dependencies and build frontend assets
echo "Building frontend assets..."
if [ ! -d "node_modules" ]; then
    npm install --legacy-peer-deps
fi
npm run build

# 9. Fix directory permissions
echo "Setting directory permissions..."
chown -R www-data:www-data /home/site/wwwroot/storage
chown -R www-data:www-data /home/site/wwwroot/bootstrap/cache
chmod -R 755 /home/site/wwwroot/storage
chmod -R 755 /home/site/wwwroot/bootstrap/cache

# 10. Configure Nginx to point to /public directory
echo "Configuring Nginx..."
sed -i "s|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g" /etc/nginx/sites-available/default

# 11. Start Nginx
echo "Starting Nginx..."
nginx -g daemon off;