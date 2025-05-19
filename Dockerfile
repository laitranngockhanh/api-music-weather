FROM php:8.1-fpm

# Cài đặt phụ thuộc hệ thống và tiện ích mở rộng PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql mbstring xml zip bcmath

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Đặt thư mục làm việc
WORKDIR /var/www/html

# Sao chép mã nguồn
COPY . .

# Cài đặt phụ thuộc Composer
RUN composer install --no-dev --prefer-dist --no-scripts --no-progress --optimize-autoloader --verbose || { echo "Composer install failed"; exit 1; }

# Phân quyền
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/resources/views
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 755 /var/www/html/resources/views

# Xóa cache và tối ưu hóa
RUN php artisan route:clear
RUN php artisan config:clear
RUN php artisan optimize

# Chạy PHP built-in server
CMD ["php", "-S", "0.0.0.0:$PORT", "-t", "public"]