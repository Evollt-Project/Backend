FROM php:8.2-fpm-alpine

WORKDIR /var/www/app

# Установка необходимых библиотек и GD
RUN apk add --no-cache \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libwebp-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql

