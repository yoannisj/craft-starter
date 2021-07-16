# @see https://docs.docker.com/engine/reference/builder/

# Define build arguments available outside build stage (before first FROM instruction)
# @note value can be overriden by environment variable defined for this service in docker-compose.yml
#   or by environment variables defined when running the `docker build` command
ARG NGINX_VERSION=1.19
ARG ENVIRONMENT=production

# Base this image on corresponding version of official nginx image
# @see https://hub.docker.com/_/nginx
FROM nginx:${NGINX_VERSION}

# Make build arguments available in build stage (after FROM instruction)
# @see https://docs.docker.com/engine/reference/builder/#understand-how-arg-and-from-interact
ARG NGINX_VERSION
ARG ENVIRONMENT

# Expose NGINX_VERSION as environment variable (available in containers created with this image)
ENV NGINX_VERSION=${NGINX_VERSION}
ENV ENVIRONMENT=${ENVIRONMENT}

# Copy localhost SSL certificate
# COPY ./services/nginx/ssl/localhost.crt /etc/nginx/localhost.crt
# COPY ./services/nginx/ssl/localhost.key /etc/nginx/localhost.key

# Use custom NGINX config file(s)
COPY ./services/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./services/nginx/security-headers.conf /etc/nginx/security-headers.conf

# Copy public project files into nginx root directory
# and make php user 'www-data' the owner
COPY --chown=www-data:www-data ./web /var/www/html/web
