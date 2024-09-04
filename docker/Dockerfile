# Use the official PHP image as a base image with Apache
FROM php:8.3-apache

# Set the working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the existing application directory contents to the working directory
COPY ./code /var/www/html

# Set permissions for Laravel
#RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Apache virtual host configuration
COPY ./docker/apache-config.conf /etc/apache2/sites-available/000-default.conf

# Expose port 80
ARG DEFAULT_HTTP_PORT=80
EXPOSE $DEFAULT_HTTP_PORT

# Start Apache in the foreground
CMD ["apache2-foreground"]
