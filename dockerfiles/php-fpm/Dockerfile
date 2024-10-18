FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    unzip git \
    && docker-php-ext-install intl pdo_pgsql opcache bcmath\
    && rm -rf /var/lib/apt/lists/*

# Копируем исходный код приложения
COPY . /var/www

WORKDIR /var/www

# Включаем кеширование с помощью opcache
COPY dockerfiles/php-fpm/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN chmod -R 777 var

RUN php composer.phar install --optimize-autoloader
