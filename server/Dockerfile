FROM php:7-fpm

RUN docker-php-ext-install mysqli \
    && pecl install redis-4.3.0 \
    && docker-php-ext-enable redis