FROM php:7.2-fpm
MAINTAINER "Constantine Derkach <kosderkach@gmail.com>"

RUN apt-get update && apt-get install -y \
  libpng-dev \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libxpm-dev \
  libvpx-dev \
  libxml2-dev \
  libpq-dev \
  libzip-dev \
  supervisor \
  libgmp-dev

RUN pecl install grpc \
     && pecl install protobuf \
     && docker-php-ext-enable grpc protobuf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-configure gd \
        --with-gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

RUN docker-php-ext-configure opcache --enable-opcache

RUN docker-php-ext-install pdo \
  pdo_pgsql \
  pgsql \
  xml \
  zip \
  gd \
  sockets \
  opcache

COPY . /var/fcm
WORKDIR /var/fcm

RUN composer install --no-interaction

EXPOSE 9000
