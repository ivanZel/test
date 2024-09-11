FROM php:8.2-fpm

RUN apt update \
    && docker-php-ext-install exif \
    && apt install -y g++ git libicu-dev zip libzip-dev zip libfreetype6-dev libjpeg62-turbo-dev libssl-dev libpng-dev \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable exif \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && pecl install mongodb \
    && echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb-ext.ini \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
RUN mkdir -p /var/www/html/var/log
RUN mkdir -p /var/www/html/var/cache
RUN git config --global user.email "you@example.com" \
    && git config --global user.name "Your Name"


