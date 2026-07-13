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

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

# Fix: pastikan cuma satu MPM yang aktif (mpm_prefork, wajib untuk mod_php)
RUN a2dismod mpm_event mpm_worker 2>/dev/null; a2enmod mpm_prefork

RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]