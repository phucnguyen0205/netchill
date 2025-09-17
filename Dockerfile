# Stage 1: Composer
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader
COPY . .

# Stage 2: PHP Apache
FROM php:8.2-apache

# Cài extension Laravel cần
RUN docker-php-ext-install pdo pdo_mysql

# Copy source từ stage vendor
COPY --from=vendor /app /var/www/html

# Trỏ DocumentRoot sang public
WORKDIR /var/www/html
RUN rm -rf /var/www/html/index.html \
    && sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/apache2.conf \
    && a2enmod rewrite

# Phân quyền cho storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
