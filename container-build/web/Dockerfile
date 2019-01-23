#
# Use this dockerfile to run the application.
#
# Start the server using docker-compose:
#
#   docker-compose build
#   docker-compose up
#
# You can install dependencies via the container:
#
#   docker-compose run readbooks composer install
#
# You can manipulate dev mode from the container:
#
#   docker-compose run readbooks composer development-enable
#   docker-compose run readbooks composer development-disable
#   docker-compose run readbooks composer development-status
#
# OR use plain old docker 
#
#   docker build -f Dockerfile-dev -t readbooks .
#   docker run -it -p "8080:80" -v $PWD:/var/www readbooks
#
FROM php:7.2-apache

RUN apt-get update \
 && apt-get install -y vim git zlib1g-dev mysql-client libzip-dev \
 && docker-php-ext-install zip mysqli pdo_mysql \
 && pecl install xdebug \
 && docker-php-ext-enable xdebug \

&& echo 'xdebug.remote_enable=on' >> /usr/local/etc/php/conf.d/xdebug.ini \
&& echo 'xdebug.remote_host=host.docker.internal' >> /usr/local/etc/php/conf.d/xdebug.ini \
# set autostart to on, so API calls auto trigger debugger
#&& echo 'xdebug.remote_autostart=0' >> /usr/local/etc/php/conf.d/xdebug.ini \

 && a2enmod rewrite \
 && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
 && mv /var/www/html /var/www/public \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer \
 && echo "AllowEncodedSlashes On" >> /etc/apache2/apache2.conf

WORKDIR /var/www
