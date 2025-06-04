FROM nginx:1.27.3-alpine3.20 AS base

# Update system and install required packages
RUN apk upgrade && \
    apk --update add --no-cache openssl dcron

# Clean up
RUN rm -rf /var/cache/apk/*

# Make html directory
RUN mkdir -p /var/www/html

FROM base AS local

# Copy configuration files
RUN mv /etc/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf.bak
ADD ./nginx/nginx-dev.conf.template /etc/nginx/templates/nginx-dev.conf.template

# Create SSL certificate
RUN mkdir -p /etc/nginx/ssl/live/${APP_DOMAIN}
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/nginx/ssl/live/${APP_DOMAIN}/nginx-selfsigned.key -out /etc/nginx/ssl/live/${APP_DOMAIN}/nginx-selfsigned.crt -subj "/C=US/ST=Denial/L=Springfield/O=Dis/CN=${APP_DOMAIN}"

FROM base AS production

# Copy Nginx configuration
COPY ./nginx/nginx-prod.conf /etc/nginx/conf.d/default.conf
