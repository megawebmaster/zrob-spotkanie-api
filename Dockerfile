FROM trafex/php-nginx:latest
LABEL org.opencontainers.image.authors="megawebmaster@gmail.com"

USER root

# Install Nginx and PHP with required dependencies and extensions
RUN apk --no-cache add gettext libpq unzip \
                       php83-iconv php83-pdo php83-pdo_pgsql php83-simplexml

COPY --chmod=0755 docker/entrypoint.sh /docker-entrypoint.sh
COPY --chmod=0755 docker/nginx/default.conf.template /etc/nginx/conf.d/

# Copy Composer from the official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create required env variables
ARG DATABASE_URL=""
ARG PORT=8880

RUN chown nobody:root /etc/nginx/conf.d && chown nobody:root /etc/nginx/conf.d/default.conf
RUN chown nobody:root /var/www && chmod 0775 /var/www

USER nobody
WORKDIR /var/www/html

# Install dependencies
COPY --chown=nobody:nobody composer.json composer.lock ./
RUN composer install --quiet --no-dev --no-scripts

# Copy source code
COPY --chown=nobody:nobody . .

EXPOSE $PORT

ENTRYPOINT ["/docker-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:$PORT/fpm-ping || exit 1
