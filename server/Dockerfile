FROM php:7-fpm

RUN docker-php-ext-install mysqli

RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

