FROM php:7.2-apache
MAINTAINER Razaq <abdrkasali@gmail.com>

# Installing dependencies
RUN apt-get update && apt-get upgrade -y
RUN apt-get -y install \
    bzip2 \
    freetds-dev \
    git \
    libfontconfig \
    libfreetype6-dev \
    libicu-dev \
    libjpeg-dev \
    libldap2-dev \
    libmcrypt-dev \
    default-libmysqlclient-dev \
    libpng-dev \
    libpq-dev \
    libwebp-dev \
    libxml2-dev \
    zlib1g-dev \
    zip \
    vim \
    wget \
    unzip \
    curl \
    cron

RUN docker-php-ext-install pdo pdo_mysql \
    -j5 gd mbstring mysqli

RUN pecl install redis \
    && pecl install xdebug \
    && docker-php-ext-enable redis xdebug

RUN a2enmod rewrite

# Download and install Composer
RUN php -r "readfile('https://getcomposer.org/installer');" | php &&\
    mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer


WORKDIR /var/www
COPY . /var/www

RUN composer update

COPY ./docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]