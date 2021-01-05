FROM php:7.2.8-fpm

ENV TZ=Europe/Moscow
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

COPY ./docker/php.ini /usr/local/etc/php/

RUN apt-get update

RUN apt-get install -y \
    wget \
    libpq-dev \
    libzip-dev \
    zlib1g-dev \
    chromium \
    libicu-dev \
    g++ \
    zip \
    unzip

ENV PANTHER_NO_SANDBOX 1

RUN docker-php-ext-install  zip
RUN docker-php-ext-install  intl
RUN docker-php-ext-install  opcache

RUN pecl install xdebug-2.9.4 &&\
    docker-php-ext-enable xdebug &&\
    echo "xdebug.remote_enable=on" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.remote_host=172.17.0.1" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.remote_log=/var/log/xdebug.log" >> /usr/local/etc/php/php.ini


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/symfony/repo/branch/backend
COPY ./ /var/www/symfony/repo/branch/backend

RUN mkdir -p  /var/www/symfony/repo/branch/backend/var/cache && \
    chown -R www-data:www-data /var/www/symfony/repo/branch/backend/var/

RUN /usr/local/bin/composer install

EXPOSE 9000
