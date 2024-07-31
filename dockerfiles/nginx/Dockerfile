FROM nginx:1.19

COPY ./dockerfiles/nginx/*.conf /etc/nginx/
COPY ./dockerfiles/nginx/conf.d/*  /etc/nginx/conf.d/

RUN echo "Europe/Moscow" > /etc/timezone \
    && ln -sf /usr/share/zoneinfo/Europe/Moscow /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata

RUN mkdir -p /var/www/public/bundles/
COPY . /var/www/
#COPY --from=registry.bank131.ru/131/admin-board/php-fpm:latest /var/www/public/bundles /var/www/public/bundles/
COPY ./public/ /var/www/public/

#RUN rm -f /etc/nginx/conf.d/default.conf

#COPY --from=registry.bank131.ru/131/admin-board/php-fpm:latest /var/www/public/bundles /var/www/public/bundles