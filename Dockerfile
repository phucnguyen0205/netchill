# Sử dụng PHP 8.2 kèm Apache
FROM php:8.2-apache

# Cài các extension cần thiết cho Laravel
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath opcache

# Bật rewrite module của Apache
RUN a2enmod rewrite

# Cài đặt Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Đặt thư mục làm việc
WORKDIR /var/www/html

# Copy toàn bộ source vào container
COPY . /var/www/html

# Cài đặt Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Set quyền cho storage và bootstrap
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose cổng 80
EXPOSE 80

# Khởi chạy Apache
CMD ["apache2-foreground"]

