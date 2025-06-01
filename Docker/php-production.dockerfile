FROM php:8.3.16-fpm-alpine3.20 AS base

# Modify php fpm configuration
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Update system and install required packages
RUN apk upgrade &&  \
    apk --update add --no-cache \
    bash \
    curl \
    git \
    libzip-dev \
    zip \
    unzip \
    libmemcached-dev \
    libmcrypt-dev \
    libxml2-dev \
    imagemagick-dev

# Install PHP extensions
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions gd pdo_mysql mysqli zip bcmath pcntl memcached mcrypt redis
RUN docker-php-ext-enable memcached mcrypt redis

# Clean up
RUN rm -rf /var/cache/apk/*

FROM base AS production

# If APP_ENV is production, copy the application files
COPY ./Laravel /var/www/html

# Set permissions for directories
RUN chmod -R 755 /var/www/html
RUN chmod -R 776 /var/www/html/storage
RUN chmod -R 776 /var/www/html/bootstrap/cache

USER www-data

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]

FROM base AS clean

# Clean stage does not need additional steps

USER www-data

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]

FROM base AS init

# Copy entrypoint script
COPY ./Docker/php/php-init.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER www-data

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
