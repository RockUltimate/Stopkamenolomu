#!/bin/sh
set -e

cd /var/www/html

# Persist APP_KEY across redeploys via ENV_PATH (host-mounted dir/file).
if [ -n "$ENV_PATH" ] && [ -s "$ENV_PATH" ]; then
  cp "$ENV_PATH" .env
fi
[ -f .env ] || cp .env.example .env
# Stateless site: no database needed
sed -i 's/^SESSION_DRIVER=.*/SESSION_DRIVER=file/' .env
sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
if ! grep -q '^APP_KEY=.\+' .env 2>/dev/null; then
  php artisan config:clear >/dev/null 2>&1 || true
  php artisan key:generate --force
  [ -n "$ENV_PATH" ] && cp .env "$ENV_PATH"
fi
php artisan config:cache >/dev/null 2>&1 || true

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R nginx:nginx storage bootstrap/cache 2>/dev/null || true

php-fpm -D
nginx -g "daemon off;"
