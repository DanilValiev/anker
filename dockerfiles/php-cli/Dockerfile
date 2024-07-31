FROM registry.bank131.ru/131/dev-tools/php/8.1-composer as builder

ARG COMPOSER_ARGUMENTS="--optimize-autoloader"
COPY . /var/www/
WORKDIR /var/www/

RUN rm -r ./dockerfiles
RUN composer install ${COMPOSER_ARGUMENTS}


FROM registry.bank131.ru/131/dev-tools/php/8.1-cli:2.1.1 as cli-worker

RUN apt-get update && apt-get -y install supervisor
COPY ./dockerfiles/php-cli/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY --from=builder /var/www /var/www

RUN mkdir -p /var/www/var/ && chmod -R 777 /var/www/var

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]