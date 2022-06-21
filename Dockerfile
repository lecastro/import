FROM tradeupgroup01/php:8.1.4-fpm-alpine

COPY php.ini /usr/local/etc/php/php.ini

COPY . /var/www/html/

RUN cd /var/www/html/
RUN chmod -R 755 /var/www/html/*
RUN chmod -R 755 /var/www/html/.env
RUN chmod -R 777 /var/www/html/storage/

RUN printenv
# RUN composer install

CMD ["php-fpm"]
