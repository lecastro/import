FROM tradeupgroup01/php:8.1.4-fpm-alpine

COPY php.ini /usr/local/etc/php/php.ini

COPY . /var/www/html/

RUN cd /var/www/html/
RUN chmod -R 755 /var/www/html/*
RUN chmod -R 755 /var/www/html/.env
RUN chmod -R 777 /var/www/html/storage/

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/sh -g www www
COPY --chown=www:www . /var/www/html

RUN printenv
USER www

CMD ["php-fpm"]
