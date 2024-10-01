FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    jpegoptim optipng pngquant gifsicle \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    build-essential \
    libfontconfig1 \
    libxrender1 \
    libpng-dev \
    libzip-dev \
    libpq-dev \
    unzip \
    zip \
    vim \
    git \
    curl

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql zip exif pcntl bcmath

# Configure and install GD with JPEG and Freetype support
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install gd

# Create and add www user/group
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Set working directory
WORKDIR /var/www/html

# Switch to the www user
USER www
