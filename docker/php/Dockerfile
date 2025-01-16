# Use the official PHP 8.3-FPM image as the base
FROM php:8.3-fpm

# Set environment variables for PHP
ENV PHP_MAX_INPUT_TIME="60" \
    PHP_MAX_EXECUTION_TIME="30" \
    PHP_UPLOAD_MAX_FILESIZE="2M" \
    PHP_MAX_FILE_UPLOAD="20" \
    PHP_MEMORY_LIMIT="2G" \
    PHP_OPCACHE_ENABLE="1" \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS="0" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="20000" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="256" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10" \
    PHP_OPCACHE_SAVE_COMMENTS="1" \
    PHP_REVALIDATE_FREQ="0" \
    COMPOSER_ALLOW_SUPERUSER=1

# Install necessary dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    git \
    unzip \
    zlib1g-dev \
    autoconf \
    build-essential \
    pkg-config \
    libxml2-dev \
    libmemcached-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    bcmath \
    exif \
    gd \
    intl \
    opcache \
    pdo \
    pdo_pgsql \
    soap \
    zip \
    simplexml

# Install and configure Xdebug and other PHP extensions
RUN pecl install xdebug \
    && pecl install apcu memcached \
    && docker-php-ext-enable apcu memcached \
    && rm -rf /tmp/pear

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer self-update --stable

# Configure PHP timezone
ARG TZ=Europe/Warsaw
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && printf '[Date]\ndate.timezone="%s"\n' $TZ > /usr/local/etc/php/conf.d/tzone.ini

# Add custom php.ini
COPY docker/php/php.ini /usr/local/etc/php/php.ini

# Set working directory
WORKDIR /var/www/html

# Copy Composer files first
COPY api/composer.json api/composer.lock /var/www/html/

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts
# Clean up temporary files and unnecessary dependencies

# Copy application files
COPY api /var/www/html

RUN apt-get purge -y --auto-remove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Expose PHP-FPM port
EXPOSE 9000
