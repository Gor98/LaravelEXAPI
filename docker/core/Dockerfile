FROM composer:1.9 as composer

RUN composer global require hirak/prestissimo
COPY composer.* ./

FROM php:7.4-fpm as build

WORKDIR /var/www

RUN apt-get update && apt-get upgrade -y && apt-get install -y \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    libpng-dev \
    unzip \
    inetutils-syslogd

# Install needed PHP extensions
RUN docker-php-ext-install bcmath pdo_pgsql zip gd

# Setup syslog
COPY docker/core/syslog.conf /etc/syslog.conf

FROM build

WORKDIR /var/www

RUN apt-get install -y \
    byobu \
    git \
    inetutils-ping \
    less \
    nano \
    net-tools \
    procps \
    gnupg && \
    curl -sL https://deb.nodesource.com/setup_12.x | bash - && \
    apt-get update && \
    apt-get install -y --no-install-recommends nodejs && \
    curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list && \
    apt-get update && \
    apt-get install -y --no-install-recommends yarn && \
    npm install -g npm

RUN pecl install xdebug  && docker-php-ext-enable xdebug
COPY docker/core/ext-xdebug.ini /usr/local/etc/php/conf.d/
COPY docker/core/php/local.ini /usr/local/etc/php/php.ini
COPY docker/core/start.sh /start.sh

COPY ./ /var/www

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer install

CMD ["bash", "/start.sh"]
