FROM composer:2.8.5 AS base

WORKDIR /var/www/html

FROM base AS init

COPY ./Docker/composer/composer-init.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]