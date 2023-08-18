FROM php:8.2-fpm-alpine

WORKDIR "/var/www"

RUN apk update && apk add --no-cache \
    vim \
    bash

RUN apk update && apk add --no-cache \
        postgresql-dev \
        freetype-dev \
        autoconf \
        g++ gcc make \
        libzip-dev zip \
        linux-headers

RUN docker-php-ext-install \
        pdo_pgsql \
        pgsql \
        zip \
        exif \
    && pecl install xdebug-3.2.1 \
    && pecl install -o -f redis \
    && docker-php-ext-enable \
        xdebug \
        redis \
    && rm -rf /tmp/pear

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.5.3 \
    && composer --version

RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www

USER www
