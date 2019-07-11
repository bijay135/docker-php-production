FROM php:7.1.0-fpm

RUN docker-php-ext-install mysqli \
	&& pecl install redis-3.0.0 \
	&& docker-php-ext-enable redis
	
    # && apt-get update \
    # && apt-get install -y libmemcached-dev zlib1g-dev \
    # && pecl install memcached \
    # && docker-php-ext-enable memcached