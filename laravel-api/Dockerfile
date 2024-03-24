# Use PHP image with PHP-FPM as the base image
FROM php:8.3-fpm AS build

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    nginx \
    && docker-php-ext-install zip pdo pdo_mysql

# Copy existing application directory contents
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel dependencies
RUN composer install

# Remove the default Nginx configuration file
RUN rm /etc/nginx/sites-enabled/default

# Copy custom Nginx configuration
COPY laravel.conf /etc/nginx/sites-available/

# Symlink the configuration file
RUN ln -s /etc/nginx/sites-available/laravel.conf /etc/nginx/sites-enabled/

# Expose port 80 for Nginx
EXPOSE 80
EXPOSE 8000

# Start both Nginx and PHP-FPM
CMD service nginx start && php-fpm

