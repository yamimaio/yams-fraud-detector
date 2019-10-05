FROM php:7.3-apache

RUN apt-get update \
    && apt-get install -y zip unzip zlib1g-dev libzip-dev\
    && docker-php-ext-install zip

# Apache config
RUN a2enmod rewrite
COPY ./docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
# PHP config
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

RUN service apache2 restart

COPY composer.json /app/composer.json
COPY composer.lock /app/composer.lock

COPY docker/composer.sh /tmp/composer.sh

WORKDIR /app

RUN /bin/bash /tmp/composer.sh \
    && mv composer.phar /usr/bin/composer \
    && rm /tmp/composer.sh

RUN php -d memory_limit=-1 `which composer` install --prefer-dist --no-progress --no-suggest

# Add the application
COPY . /app