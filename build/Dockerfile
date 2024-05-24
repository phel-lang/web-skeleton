FROM php:8.3-fpm

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

RUN pecl install -o -f xdebug \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable xdebug

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN useradd -m dev
WORKDIR /srv/phel-web-skeleton
