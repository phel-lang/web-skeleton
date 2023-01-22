FROM php:8.1-fpm

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN useradd -m dev
WORKDIR /srv/phel-web-skeleton
