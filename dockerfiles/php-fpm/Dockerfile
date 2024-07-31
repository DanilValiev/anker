FROM registry.bank131.ru/131/merchant-cabinet-api/composer as builder

ARG COMPOSER_ARGUMENTS="--optimize-autoloader"
COPY . /var/www/
WORKDIR /var/www/

RUN rm -r ./dockerfiles
RUN composer install ${COMPOSER_ARGUMENTS}


FROM registry.bank131.ru/131/dev-tools/php/8.1-fpm:2.1.1 AS web-api

COPY --from=builder /var/www /var/www

ENV PHP_OPCACHE_ENABLE 1

RUN mkdir -p /var/www/var/ && chmod -R 777 /var/www/var

RUN echo "xdebug.log_level = 0" >> /etc/php/8.1/fpm/conf.d/20-xdebug.ini

CMD ["php-fpm"]