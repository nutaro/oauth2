FROM php:8.3-fpm

# Install system dependencies and PHP extensions
RUN apt update
RUN apt install git unzip libpq-dev -y
RUN docker-php-ext-install pdo_pgsql

ADD www.conf /etc/php/8.3/fpm/pool.d/www.conf

WORKDIR /var/www/backend

ADD . /var/www/backend

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install

EXPOSE 9000
CMD ["php-fpm"]
