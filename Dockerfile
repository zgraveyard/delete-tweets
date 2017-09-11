FROM php:7.1-fpm-alpine

MAINTAINER Zaher Ghaibeh <z@zah.me>

ADD ./ /var/www

WORKDIR /var/www

RUN mkdir config \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --quiet \
    && rm composer-setup.php \
    && php composer.phar install