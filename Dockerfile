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

RUN a2enmod rewrite

# Debug: cari SEMUA baris LoadModule terkait mpm di seluruh config Apache
RUN echo "=== CARI SEMUA REFERENSI MPM ===" && grep -rn "mpm" /etc/apache2/ 2>/dev/null

# Debug: jalankan apache config test langsung untuk lihat error detailnya
RUN echo "=== APACHE CONFIG TEST ===" && apache2ctl configtest 2>&1 || true

EXPOSE 80

CMD ["apache2-foreground"]