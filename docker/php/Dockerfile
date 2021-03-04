# @see https://docs.docker.com/engine/reference/builder/

# Base this image on the official docker php image
# - OS: debian:buster-slim
# - PHP version (FPM): 7.4
FROM php:7.4-fpm

# Define build variables with default values (available when image is built)
# @note value can be overriden by environment variable defined for this service in docker-compose.yml
# or by environment variables defined in the `docker build` command
ARG ENVIRONMENT=production

# Define environment variables with default values (available in containers created with this image)
ENV ENVIRONMENT=$ENVIRONMENT

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
RUN mv "$PHP_INI_DIR/php.ini-${ENVIRONMENT}" "$PHP_INI_DIR/conf.d/php.ini"

# Install composer in /usr/local/bin/
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

# Create directories where composer read/writes its config, cache, etc.
# and make php user 'www-data' the owner
RUN mkdir /var/www/.composer && \
  mkdir /var/www/.composer/cache && \
  chown -R www-data:www-data /var/www/.composer

# Copy project files into image (with correct file ownership)
COPY --chown=www-data:www-data ./web /var/www/html/web
COPY --chown=www-data:www-data ./craftcms/config /var/www/html/craftcms/config
COPY --chown=www-data:www-data ./craftcms/modules /var/www/html/craftcms/modules
COPY --chown=www-data:www-data ./craftcms/storage /var/www/html/craftcms/storage
COPY --chown=www-data:www-data ./craftcms/templates /var/www/html/craftcms/templates
COPY --chown=www-data:www-data ./vendor /var/www/html/vendor
COPY --chown=www-data:www-data ./composer.json /var/www/html/
COPY --chown=www-data:www-data ./composer.lock /var/www/html/
COPY --chown=www-data:www-data ./craft /var/www/html/

# Switch to PHP user to run composer (same user owning the app files)
USER www-data

# Install php dependency packages with composer, in the 'vendor' directory
# and make php user 'www-data' the owner
# - includes craft cms, its plugins, and their dependencies
RUN composer install -d /var/www/html/
