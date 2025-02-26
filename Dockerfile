# Use the official PHP image as the base image
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    tesseract-ocr \
    libreoffice \
    ocrmypdf \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Copy composer.json and composer.lock first for better caching
COPY composer.json composer.lock ./

# Set proper directory permissions
RUN chown -R www-data:www-data /var/www/html

# Install PHP dependencies
RUN composer install --no-scripts --no-autoloader

# Copy package.json and package-lock.json for better caching
COPY package.json package-lock.json ./

# Install JS dependencies
RUN npm i

# Copy application code
COPY . .

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate the optimized autoloader
RUN composer dump-autoload --optimize

# Run all remaining composer scripts
RUN composer run-script post-autoload-dump

# Build assets
RUN npm run build

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]