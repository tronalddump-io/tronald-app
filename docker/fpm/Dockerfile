FROM php:7.2.0-fpm-alpine3.7

MAINTAINER Mathias Schilling <m@matchilling.com>

ENV APP_PATH /var/www/html

# Install utils
RUN apk add --update bash && \
    apk add --update git

# Install imagick
RUN set -ex && \
    apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS imagemagick-dev libtool && \
    export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" && \
    pecl install imagick-3.4.3  && \
    docker-php-ext-enable imagick && \
    apk add --no-cache --virtual .imagick-runtime-deps imagemagick && \
    apk del .phpize-deps

# Install postgresql
RUN apk add --update postgresql-dev && \
    apk add --update postgresql-client && \
    docker-php-ext-install pdo pgsql pdo_pgsql

# Clean apk dir
RUN rm -rf /var/cache/apk/*

# Create app directory
RUN mkdir -p $APP_PATH
WORKDIR $APP_PATH

# Install app dependencies
RUN curl --silent --show-error https://getcomposer.org/installer | php
COPY composer.lock $APP_PATH
COPY composer.json $APP_PATH
RUN php composer.phar install

# Bundle app source
COPY . $APP_PATH

CMD ["php-fpm"]
