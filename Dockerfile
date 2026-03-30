FROM php:8.3-apache

# Enable Apache mod_rewrite for Slim routing
RUN a2enmod rewrite

# Set Apache document root to /public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf

# Allow .htaccess overrides
RUN sed -i 's/AllowOverride None/AllowOverride All/g' \
    /etc/apache2/apache2.conf

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install additional PHP extensions as needed, for example:
# RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first (for caching)
COPY composer.json composer.lock* ./

RUN composer install --no-dev --no-interaction --optimize-autoloader --no-scripts

# Copy application
COPY . .

# Optimize autoload
RUN composer dump-autoload --optimize

# Ensure storage is writable
RUN mkdir -p storage/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage

EXPOSE 80