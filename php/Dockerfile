FROM php:8.2-fpm

WORKDIR /

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y \
        vim \
        # postfix \
        sendmail \
        # zlib1g-dev \
        # libzip-dev \
        # sendmail-bin \
        mailutils \
        bsd-mailx \
        # mailx \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev && \
        # freetype-dev && \
        # libjpeg-turbo8-dev &&  \
        # apt-get install -y libpng-dev libfreetype6-dev && \
    docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ && \
    docker-php-ext-install -j$(nproc) gd

# RUN apt-get update -y && apt-get install -y mailx

RUN echo "sendmail_path=/usr/sbin/sendmail -t -i -X /var/log/mail.log" >> /usr/local/etc/php/conf.d/sendmail.ini

# RUN docker-php-ext-install zip

RUN sed -i '/#!\/bin\/sh/aservice sendmail restart' /usr/local/bin/docker-php-entrypoint

RUN sed -i '/#!\/bin\/sh/aecho "$(hostname -i)\t$(hostname) $(hostname).localhost" >> /etc/hosts' /usr/local/bin/docker-php-entrypoint

# And clean up the image

RUN rm -rf /var/lib/apt/lists/*

# COPY ./php/mailconfig.ini /usr/local/etc/php/conf.d