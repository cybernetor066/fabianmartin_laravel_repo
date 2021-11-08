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
RUN chown -R www-data:www-data *

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
                



# RUN set -ex \
#     && apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS imagemagick-dev libtool \
#     && export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" \
#     && pecl install imagick-3.4.3 \
#     && docker-php-ext-enable imagick \
#     && apk add --no-cache --virtual .imagick-runtime-deps imagemagick \
#     && apk del .phpize-deps

ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions imagick






RUN cd /var/www/html && composer update && composer install
# RUN cd /var/www/html && composer update && composer install && php artisan key:generate



                
# Imagick https://github.com/docker-library/php/issues/794
# RUN su - devuser && cd /var/www/html && composer install && php artisan key:generate
