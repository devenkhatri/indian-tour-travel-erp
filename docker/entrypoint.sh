#!/bin/sh
set -e

# ==============================================================================
# Container Entrypoint for BharatTravel ERP
# ==============================================================================

# Ensure required storage and bootstrap directories exist
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/storage/app/public \
         /var/www/html/bootstrap/cache

# Fix ownership and permissions for web user
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# If vendor directory autoload is missing (e.g. host bind mount on first run), install dependencies
if [ ! -f /var/www/html/vendor/autoload.php ]; then
    echo "Composer dependencies missing. Installing vendor packages..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Auto-generate application encryption key if missing (only in primary app container to prevent race conditions)
if [ -z "$CONTAINER_ROLE" ] && [ -f /var/www/html/.env ]; then
    if ! grep -q "^APP_KEY=base64:" /var/www/html/.env && ! grep -q "^APP_KEY=.*[a-zA-Z0-9]" /var/www/html/.env; then
        echo "APP_KEY is empty. Generating new application key..."
        php artisan key:generate --force --no-interaction
    fi
fi

# Create public storage symlink if it does not exist
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link --no-interaction 2>/dev/null || true
fi

# Execute passed command (php-fpm, artisan queue:work, artisan schedule:work, etc.)
exec "$@"
