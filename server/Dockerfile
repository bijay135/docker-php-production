FROM php:7-fpm

RUN docker-php-ext-install mysqli \
	&& pecl install redis \ 
	&& docker-php-ext-enable redis



