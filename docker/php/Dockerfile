# @see https://docs.docker.com/engine/reference/builder/

# Define build arguments available outside build stage (before first FROM instruction)
# @note value can be overriden by environment variable defined for this service in docker-compose.yml
#   or by environment variables defined when running the `docker build` command
ARG PHP_VERSION=7.4
ARG ENVIRONMENT=production

# Base this image on corresponding version of official mariadb image
FROM php:$PHP_VERSION-fpm

# Make build arguments available in build stage (after FROM instruction)
# @see https://docs.docker.com/engine/reference/builder/#understand-how-arg-and-from-interact
ARG PHP_VERSION
ARG ENVIRONMENT

# Expose NGINX_VERSION as environment variable (available in containers created with this image)
ENV PHP_VERSION=${PHP_VERSION}
ENV ENVIRONMENT=${ENVIRONMENT}

# Install OS- and PHP-level dependencies for Craft-CMS
# @see https://craftcms.com/docs/3.x/requirements.html
# - includes installing curl to make http requests
# - includes installing the mariadb client
# - includes installing git for composer
# - includes installing of imagick
# - includes installing and configuring of required php extensions
RUN apt-get update && apt-get install -y \
  libssl-dev \
  libbz2-dev \
  libzip-dev \
  libonig-dev \
  libicu-dev \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  libmagickwand-dev \
  autoconf ca-certificates curl git zip unzip g++ mariadb-client \
  && \
  pecl install imagick-3.4.3 \
  && \
  docker-php-ext-install \
  bcmath bz2 exif \
  ftp gd gettext opcache \
  shmop sockets sysvmsg sysvsem sysvshm \
  zip iconv pdo_mysql intl soap \
  && \
  docker-php-ext-configure gd --with-freetype --with-jpeg \
  && \
  docker-php-ext-enable imagick

# Use project's configuration for php-fpm
# @link https://mattgrayisok.com/logging-from-craft-cms-in-docker
COPY ./docker/php/docker.conf /usr/local/etc/php-fpm.d/docker.conf

# Load default PHP settings based on current environment
# @link https://hub.docker.com/_/php#configuration
RUN cp "${PHP_INI_DIR}/php.ini-${ENVIRONMENT}" "${PHP_INI_DIR}/conf.d/php-defaults-${ENVIRONMENT}.ini"

# Load custom php settings for current environment
COPY ./docker/php/php.ini-${ENVIRONMENT} ${PHP_INI_DIR}/conf.d/php-user-${ENVIRONMENT}.ini

# Install composer in /usr/local/bin/
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

# Create directories where composer read/writes its config, cache, etc.
# and make php user 'www-data' the owner
RUN mkdir -p /var/www/.composer/cache && \
  chown -R www-data:www-data /var/www/.composer

# Including the script file to run craft's queue in a "keep alive" loop
COPY --chown=www-data:www-data ./docker/php/run_craft_queue.sh ./
RUN chmod a+x run_craft_queue.sh

# Copy composer dependency files
COPY --chown=www-data:www-data ./composer.json ./
COPY --chown=www-data:www-data ./composer.lock ./
COPY --chown=www-data:www-data ./vendor ./vendor

# Copy script files to run Craft commands and Craft Queue (in a "stay-alive" loop)
COPY --chown=www-data:www-data ./craft ./
COPY --chown=www-data:www-data ./docker/php/run_craft_queue.sh ./
RUN chmod a+x craft run_craft_queue.sh

# Copy Craft-CMS project files
COPY --chown=www-data:www-data ./craftcms/config ./craftcms/config
COPY --chown=www-data:www-data ./craftcms/modules ./craftcms/modules
COPY --chown=www-data:www-data ./craftcms/templates ./craftcms/templates
COPY --chown=www-data:www-data ./craftcms/storage ./craftcms/storage

# Copy web files (available publicly via web server)
COPY --chown=www-data:www-data ./web ./web

# Switch to PHP user to run composer (same user owning the app files copied above)
USER www-data

# Install php dependency packages with composer, in the 'vendor' directory
# and make php user 'www-data' the owner
# - includes craft cms, its plugins, and their dependencies
RUN composer install --verbose --no-progress --optimize-autoloader --no-interaction
