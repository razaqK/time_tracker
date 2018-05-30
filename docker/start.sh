#!/usr/bin/env bash

php -f docker/vhost.conf.php > /etc/apache2/sites-available/000-default.conf

chown -R www-data:www-data /var/www

mkdir -p /var/log/apache2 && \
chown -R root:adm /var/log/apache2 && \
chmod -R 750 /var/log/apache2

service apache2 start

tail -f /var/log/apache2/access.log