FROM php:8.2-cli
# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y zip unzip dos2unix

# Install PHP extensions
RUN docker-php-ext-install sockets

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer