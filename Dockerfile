# ---- build stage: install deps + prebuild caches ----
FROM composer:2 AS vendor
WORKDIR /build
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

FROM php:8.4-fpm-alpine AS prebuild
RUN apk add --no-cache icu-dev libzip-dev \
    && docker-php-ext-install intl zip bcmath \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS || true
WORKDIR /var/www/html
COPY --from=vendor /usr/bin/composer /usr/bin/composer
COPY --from=vendor /build/vendor ./vendor
COPY . .
RUN composer dump-autoload --optimize \
    && php artisan package:discover --ansi \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && php artisan config:cache && php artisan route:cache && php artisan view:cache

# ---- runtime ----
FROM php:8.4-fpm-alpine
RUN apk add --no-cache nginx \
    && rm -f /etc/nginx/conf.d/default.conf
WORKDIR /var/www/html
COPY --from=prebuild /var/www/html /var/www/html
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/zz-app.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh \
    && chown -R nginx:nginx storage bootstrap/cache 2>/dev/null || true

EXPOSE 80
CMD ["/start.sh"]
