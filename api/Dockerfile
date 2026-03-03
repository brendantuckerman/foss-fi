FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    postgresql-dev \
    icu-dev \
    zip \
    unzip \
    && docker-php-ext-install \
        pdo_pgsql \
        intl \
        opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
