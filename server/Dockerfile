FROM php:7-fpm

RUN docker-php-ext-install mysqli \
	&& pecl install redis \
	&& docker-php-ext-enable redis
	
	# && apt-get update \
	# && apt-get install -y libmemcached-dev zlib1g-dev \
	# && pecl install memcached \
	# && docker-php-ext-enable memcached