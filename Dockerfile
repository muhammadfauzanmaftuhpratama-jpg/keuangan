FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip zip \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install gd zip intl mysqli pdo pdo_mysql

EXPOSE 80

CMD ["apache2-foreground"]