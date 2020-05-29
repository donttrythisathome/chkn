FROM php:7.2-fpm
MAINTAINER "Constantine Derkach <kosderkach@gmail.com>"

RUN apt-get update && apt-get install -y \
  libxpm-dev \
  libvpx-dev \
  libxml2-dev \
  libpq-dev \
  libzip-dev \
  libgmp-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql


RUN docker-php-ext-install pdo \
  pdo_pgsql \
  pgsql \
  xml \
  zip

COPY . /var/fcm
WORKDIR /var/fcm

RUN composer install --no-interaction

EXPOSE 9000
