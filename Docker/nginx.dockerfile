FROM nginx:1.27.3-alpine3.20 AS base

# environment arguments
ARG APP_DOMAIN
ARG APP_ENV

# Dialout group in alpine linux conflicts with MacOS staff group's gid, whis is 20. So we remove it.
RUN delgroup dialout

# Update system and install required packages
RUN apk upgrade && \
    apk --update add --no-cache openssl dcron

# Clean up
RUN rm -rf /var/cache/apk/*

# Copy configuration files
RUN mv /etc/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf.bak
ADD ./nginx/nginx-dev.conf.template /etc/nginx/templates/nginx-dev.conf.template

# Make html directory
RUN mkdir -p /var/www/html

FROM base AS local

# Create SSL certificate
RUN mkdir -p /etc/nginx/ssl/live/${APP_DOMAIN}
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/nginx/ssl/live/${APP_DOMAIN}/nginx-selfsigned.key -out /etc/nginx/ssl/live/${APP_DOMAIN}/nginx-selfsigned.crt -subj "/C=US/ST=Denial/L=Springfield/O=Dis/CN=${APP_DOMAIN}"

FROM base AS production
