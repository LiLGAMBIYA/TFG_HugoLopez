FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    bash \
    git \
    icu-dev \
    libzip-dev \
    postgresql-dev \
    unzip \
    zip \
    $PHPIZE_DEPS \
    && docker-php-ext-install intl opcache pdo pdo_pgsql zip \
    && apk del $PHPIZE_DEPS

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/entrypoint.sh /usr/local/bin/app-entrypoint
RUN chmod +x /usr/local/bin/app-entrypoint

COPY . .

RUN mkdir -p var/cache var/log public/build \
    && chown -R www-data:www-data var public

ENTRYPOINT ["app-entrypoint"]
CMD ["php-fpm"]
