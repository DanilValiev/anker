FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    libicu-dev git unzip\
&& rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install intl
RUN pecl install xdebug\
    && docker-php-ext-enable xdebug

RUN apt-get update && apt-get install -y \
    libpq-dev \
&& docker-php-ext-install pdo_pgsql bcmath