FROM php:7.4-fpm-alpine

RUN apk update; \
    apk upgrade;

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN adduser --uid 1001 --disabled-password --home /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser
RUN addgroup devuser www-data
RUN addgroup devuser root

RUN chgrp -R  www-data /var/www/html
RUN chmod -R g+r /var/www/html/

RUN apt-get update \
    && apt-get -y --no-install-recommends install  php7.4-pgsql php7.4-gd php-redis \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*\
    && apk add --no-cache zip libzip-dev \
    && docker-php-ext-configure zip\
    && docker-php-ext-install zip

RUN apk add --no-cache --virtual .deps \
                git \
                icu-libs \
                zlib \
                openssh \
                imagemagick \
                imagemagick-libs \
                imagemagick-dev
                

COPY /public_html/ /var/www/html/

# Imagick https://github.com/docker-library/php/issues/794
# RUN su - devuser && cd /var/www/html && composer install && php artisan key:generate