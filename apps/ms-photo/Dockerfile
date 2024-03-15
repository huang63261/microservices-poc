FROM php:8.2-fpm-alpine
# FROM php:8.2-cli-alpine

WORKDIR /var/www/html

RUN apk add --no-cache nginx supervisor envsubst

# Install phpize
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    apk add --no-cache freetype-dev libjpeg-turbo-dev libpng-dev libzip-dev bzip2-dev linux-headers && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-configure pcntl --enable-pcntl && \
    # Install gd zip bz2 bcmath pdo_mysql opcache sockets
    docker-php-ext-install -j$(nproc) gd zip bz2 bcmath pdo_mysql opcache sockets pcntl && \
    # Install redis
    pecl install redis && docker-php-ext-enable redis && \
    # Install swoole
    pecl install swoole && docker-php-ext-enable swoole && \
    # Remove useless package
    apk del --no-network .build-deps linux-headers

# Install composer +globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# PHP ini default
ENV PHP_EXPOSE_PHP Off
ENV PHP_DATE_TIMEZONE Asia/Taipei
ENV PHP_MEMORY_LIMIT 512M
ENV PHP_POST_MAX_SIZE 500M
ENV PHP_UPLOAD_MAX_FILESIZE 50M
ENV PHP_MAX_FILE_UPLOADS 20
ENV PHP_MAX_INPUT_VARS 5000
ENV PHP_SESSION_SAVE_HANDLER files
ENV PHP_SESSION_SAVE_PATH /var/lib/php/sessions
ENV PHP_SESSION_COOKIE_HTTPONLY 1

# OPcache defaults
ENV PHP_OPCACHE_ENABLE 0
ENV PHP_OPCACHE_ENABLE_CLI 1
ENV PHP_OPCACHE_JIT tracing
ENV PHP_OPCACHE_JIT_BUFFER_SIZE 256M
ENV PHP_OPCACHE_MEMORY_CONSUMPTION 128
ENV PHP_OPCACHE_MAX_ACCELERATED_FILES 10000
ENV PHP_OPCACHE_REVALIDATE_FREQUENCY 0
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS 0

# PHP-FPM defaults
ENV PHP_FPM_PM dynamic
ENV PHP_FPM_MAX_CHILDREN 600
ENV PHP_FPM_START_SERVERS 200
ENV PHP_FPM_MIN_SPARE_SERVERS 100
ENV PHP_FPM_MAX_SPARE_SERVERS 300
ENV PHP_FPM_MAX_REQUESTS 1000

# Nginx Config
ENV NGINX_ROOT /var/www/html/public
ENV NGINX_GLB_IP 8.8.8.8/32

# Use the default production configuration
RUN mv "${PHP_INI_DIR}/php.ini-production" "${PHP_INI_DIR}/php.ini"

# Copy the Default configuration file
COPY .docker/php/php.ini "${PHP_INI_DIR}/conf.d/php.ini"

# Copy the OPcache configuration file
COPY .docker/php/opcache.ini "${PHP_INI_DIR}/conf.d/opcache.ini"

# Copy the PHP-FPM configuration file
COPY .docker/php/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
RUN touch /var/run/php-fpm.sock

# Configure nginx
COPY .docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY .docker/nginx/default.conf /etc/nginx/template/default.conf
RUN envsubst '${NGINX_ROOT} ${NGINX_GLB_IP}' < /etc/nginx/template/default.conf > /etc/nginx/http.d/default.conf

# RUN ln -sf /dev/stdout /var/log/nginx/access.log \
#     ln -sf /dev/stderr /var/log/nginx/error.log

# Configure supervisor
RUN mkdir -p /etc/supervisor.d/
COPY .docker/supervisord.ini /etc/supervisor.d/supervisord.ini

# Building process
COPY . .
RUN composer install --no-dev
RUN chown -R nginx:nginx /var/www/html

# USER www-data

EXPOSE 8000
CMD ["supervisord", "-c", "/etc/supervisor.d/supervisord.ini"]
# CMD ["php", "artisan", "octane:swoole", "--host=0.0.0.0", "--port=8000"]
