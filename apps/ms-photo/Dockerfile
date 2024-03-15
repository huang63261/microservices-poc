FROM php:8.2-fpm-alpine

# 全域設定
WORKDIR /var/www/ms-product

# 安裝環境、安裝工具
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
RUN apk add --no-cache unzip
RUN docker-php-ext-install bcmath
RUN apk add --no-cache --virtual .build-deps autoconf g++ make && pecl install redis && apk del .build-deps
RUN docker-php-ext-enable redis

# 安裝程式依賴套件
COPY composer.* ./
RUN composer install --no-scripts && composer clear-cache
# RUN composer install --no-dev --no-scripts && composer clear-cache

# 複製程式碼
COPY . .
RUN composer run post-autoload-dump

# change www-data's uid and gid for laravel folder permisstion
RUN apk --no-cache add shadow && \
    usermod -u 1000 www-data && \
    groupmod -g 1000 www-data

# EXPOSE 9000

CMD ["php", "artisan", "serve", "--host", "0.0.0.0"]