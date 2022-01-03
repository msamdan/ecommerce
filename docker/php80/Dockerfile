FROM ubuntu:18.04

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y locales && rm -rf /var/lib/apt/lists/* \
    && localedef -i tr_TR -c -f UTF-8 -A /usr/share/locale/locale.alias tr_TR.UTF-8
ENV LANG tr_TR.utf8
ENV LANGUAGE tr_TR:tr
ENV LC_ALL tr_TR.UTF-8

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y \
    autoconf \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    locales \
    nano \
    libssl1.0 \
    libfontconfig1 \
    libxrender1 \
    libxext6

RUN apt-get update -y && apt-get upgrade -y && \
    locale-gen tr_TR && \
    locale-gen tr_TR.UTF-8 && \
    update-locale LANG="tr_TR.UTF-8" LANGUAGE="tr_TR" && \
    apt-get install software-properties-common -y && \
    add-apt-repository ppa:chris-needham/ppa -y && \
    apt-get install wget -y && \
    add-apt-repository ppa:ondrej/php -y && \
    apt-get update -y && \
    apt-get install php8.0-fpm -y && \
    apt-get remove --autoremove apache2* -y && \
    apt-get install -y php8.0-curl \
    php8.0-gd \
    php8.0-imap \
    php8.0-mbstring \
    php8.0-soap \
    php8.0-intl \
    php8.0-zip \
    php8.0-opcache \
    php8.0-imagick \
    php8.0-cli \
    php8.0-dev \
    php8.0-xsl \
    php8.0-xdebug \
    php8.0-igbinary \
    php8.0-APCu \
    php8.0-pgsql \
    zlib1g-dev \
    php8.0-dev \
    php-pear \
    supervisor \
    vim \
    openssl \
    curl && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    export LC_ALL="C" && \
    mkdir /www && \
    groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www && \
    chown -R www:www /www && \
    mkdir /run/php

WORKDIR /www

EXPOSE 9000

ENTRYPOINT [ "/usr/sbin/php-fpm8.0","-F" ]

