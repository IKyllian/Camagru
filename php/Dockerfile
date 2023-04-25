FROM php:8.2-fpm

WORKDIR /

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y \
        vim \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev && \
    docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ && \
    docker-php-ext-install -j$(nproc) gd