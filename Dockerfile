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

# Debug: lihat modul MPM SEBELUM fix
RUN echo "=== SEBELUM FIX ===" && ls -la /etc/apache2/mods-enabled/ | grep mpm || true

# Paksa matikan semua MPM, lalu aktifkan cuma prefork
RUN a2dismod mpm_event mpm_worker mpm_itk 2>/dev/null; \
    rm -f /etc/apache2/mods-enabled/mpm_event.load /etc/apache2/mods-enabled/mpm_event.conf \
          /etc/apache2/mods-enabled/mpm_worker.load /etc/apache2/mods-enabled/mpm_worker.conf \
          /etc/apache2/mods-enabled/mpm_itk.load /etc/apache2/mods-enabled/mpm_itk.conf; \
    a2enmod mpm_prefork

# Debug: lihat modul MPM SETELAH fix
RUN echo "=== SETELAH FIX ===" && ls -la /etc/apache2/mods-enabled/ | grep mpm

RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]