# =============================
# BASE IMAGE
# Official PHP 8.3 image with Apache pre-installed
# =============================
FROM php:8.3-apache


# =============================
# APACHE CONFIGURATION
# =============================

# Enable mod_rewrite for Slim routing
RUN a2enmod rewrite

# Optional: enable SSL if needed
# RUN a2enmod ssl

# Set document root to /public (keeps app files outside web root)
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf

# Allow .htaccess overrides (required for Slim routing)
RUN sed -i 's/AllowOverride None/AllowOverride All/g' \
    /etc/apache2/apache2.conf


# =============================
# SYSTEM DEPENDENCIES
# =============================

# Required for Composer and general usage
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Optional: install PHP extensions as needed
# RUN docker-php-ext-install pdo pdo_mysql


# =============================
# COMPOSER
# =============================

# Copy Composer from official image (faster and reproducible)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# =============================
# APPLICATION SETUP
# =============================

# Set APP_ENV so the app doesn't try to load a .env file at startup.
# All other config should be supplied at runtime via environment variables.
ENV APP_ENV=production

WORKDIR /var/www/html

# Copy dependency manifests first for better caching
COPY composer.json composer.lock ./

# Install production dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --optimize-autoloader \
    --no-scripts

# Copy application source
COPY . .

# Optimize autoloader after full source is available
RUN composer dump-autoload --optimize


# =============================
# PERMISSIONS
# =============================

# Create logs directory before chown so it is included in ownership change
RUN mkdir -p storage/logs

# Give Apache user ownership of all app files and write access to storage
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage


# =============================
# PORT
# =============================

EXPOSE 80