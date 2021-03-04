# @see https://docs.docker.com/engine/reference/builder/

# Base this image on the official nginx image
# @see https://hub.docker.com/_/nginx
# - OS: ddebian:buster-slim
# - Nginx Version: 1.19
FROM nginx:1.19

# Copy localhost SSL certificate
# COPY ./docker/nginx/localhost.crt /etc/nginx/localhost.crt
# COPY ./docker/nginx/localhost.key /etc/nginx/localhost.key

# Use custom NGINX config file(s)
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./docker/nginx/security-headers.conf /etc/nginx/security-headers.conf

# Copy public project files into nginx root directory
# and make php user 'www-data' the owner
COPY --chown=www-data:www-data ./web /var/www/html/web