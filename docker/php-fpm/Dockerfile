FROM php:8.5.12-fpm

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Fix permissions
RUN usermod -u 1000 www-data \
    && groupmod -g 1000 www-data

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install \
    ssmtp \
    mailutils \
    apt-utils \
    libpq-dev \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql bcmath sockets zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN pecl channel-update pecl.php.net \
    && pecl install redis-6.3.0 \
    && pecl install xdebug-3.5.3 \
    && docker-php-ext-enable redis xdebug

COPY --from=composer /usr/bin/composer /usr/bin/composer

# Copy xdebug configuration for remote debugging
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

