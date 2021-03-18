FROM php:7.4-apache

RUN apt-get update -y

RUN apt-get install -y vim less git

RUN set -ex apk --no-cache add postgresql-dev libpq-dev
RUN apt-get install -y libpq-dev libonig-dev\
    && docker-php-ext-install pdo pdo_pgsql pgsql

COPY ./php.ini /usr/local/etc/php/
COPY --from=composer:1.10.15 /usr/bin/composer /usr/bin/composer
