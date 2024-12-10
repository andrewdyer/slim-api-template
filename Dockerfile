# Stage 1: Build
FROM php:8.2-apache AS build

# Install dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y libzip-dev \
    && docker-php-ext-install zip

# Enable Apache modules
RUN a2enmod rewrite ssl

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . /var/www/

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Install PHP dependencies
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Stage 2: Runtime
FROM php:8.2-apache

# Enable Apache modules
RUN a2enmod rewrite ssl

# Set working directory
WORKDIR /var/www

# Copy application files from build stage
COPY --from=build /var/www /var/www

# Remove default html folder and rename public to html
RUN rm -rf /var/www/html \
    && mv /var/www/public /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]