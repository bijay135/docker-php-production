FROM php:7-fpm

RUN docker-php-ext-install mysqli /
	&& pecl install memcached /
	&& docker-php-ext-enable memcached