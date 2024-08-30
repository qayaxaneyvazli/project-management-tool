FROM php:8.1-fpm


RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip


RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


WORKDIR /var/www


COPY . /var/www

RUN composer install


RUN chown -R www-data:www-data /var/www

 
CMD ["php-fpm"]

EXPOSE 9000
