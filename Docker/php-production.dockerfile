FROM php:8.3.16-fpm-alpine3.20 AS base

# environment arguments
ARG UID
ARG GID

# Dialout group in alpine linux conflicts with MacOS staff group's gid, which is 20. So we remove it.
RUN delgroup dialout

# Creating user and group
RUN if [ ${UID:-0} -ne 0 ] && [ ${UID:-0} -ne 0 ]; then \
  deluser www-data && \
  getent group www-data && delgroup www-data || true && \
  addgroup -g ${GID} www-data && \
  adduser -u ${UID} -D -S -G www-data www-data && \
  install -d -m 0755 -o www-data -g www-data /home/www-data && \
  chown --changes --silent --no-dereference --recursive \
    ${UID}:${GID} \
    /home/www-data \
    /var/www/html \
;fi

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

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

USER www-data

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]

FROM base AS clean

USER www-data

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]

FROM base AS init

# Copy entrypoint script
COPY ./Docker/php/php-init.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER www-data

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
