FROM php:8.3.16-fpm-alpine3.20 AS base

# environment arguments
ARG UID
ARG GID
ARG USER

ENV UID=${UID}
ENV GID=${GID}
ENV USER=${USER}

# Dialout group in alpine linux conflicts with MacOS staff group's gid, which is 20. So we remove it.
RUN delgroup dialout

# Creating user and group
RUN addgroup -g ${GID} --system ${USER}
RUN adduser -G ${USER} --system -D -s /bin/sh -u ${UID} ${USER}

# Modify php fpm configuration to use the new user's privileges.
RUN sed -i "s/user = www-data/user = ${USER}/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = ${USER}/g" /usr/local/etc/php-fpm.d/www.conf
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

FROM base AS local

# Additional steps for local environment
RUN install-php-extensions xdebug
RUN docker-php-ext-enable xdebug

# Configure Xdebug
COPY ./Docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-xdebug.ini
RUN touch /var/log/xdebug.log && chmod 777 /var/log/xdebug.log

# Set max execution time to 120 seconds
RUN echo 'max_execution_time = 120' >> /usr/local/etc/php/conf.d/docker-php-maxexectime.ini;

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]

FROM base AS production

# If APP_ENV is production, copy the application files
COPY ./Laravel /var/www/html

# Make the application files owned by the new user
RUN chown -R ${USER}:${USER} /var/www/html

# Set permissions for directories
RUN chmod -R 755 /var/www/html
RUN chmod -R 776 /var/www/html/storage
RUN chmod -R 776 /var/www/html/bootstrap/cache

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]

FROM base AS clean

# Clean stage does not need additional steps

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]

FROM base AS init

# Copy entrypoint script
COPY ./Docker/php/php-init.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
