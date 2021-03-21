FROM php:7.4.15-fpm-buster

# PHP
RUN apt-get update && apt-get install -y \
  libmemcached-dev \
  zlib1g-dev \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  && pecl install memcached-3.1.5 \
  && docker-php-ext-enable memcached \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) pdo pdo_mysql mysqli gd 

COPY docker/php-fpm/php.ini-production "$PHP_INI_DIR/php.ini"
COPY docker/php-fpm/www.conf "/usr/local/etc/php-fpm.d/www.conf"
COPY docker/php-fpm/php-fpm.conf "/usr/local/etc/php-fpm.conf"

# Copy PHP files
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html

# Expose and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
