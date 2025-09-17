FROM php:8.2-apache

# Cài thêm extension cần cho Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Copy project vào trong container
COPY . /var/www/html

# Chuyển DocumentRoot sang thư mục public
WORKDIR /var/www/html
RUN rm -rf /var/www/html/index.html \
    && sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/apache2.conf \
    && a2enmod rewrite

# Quyền cho storage & bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Composer install (nếu render không cài sẵn)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80
CMD ["apache2-foreground"]
