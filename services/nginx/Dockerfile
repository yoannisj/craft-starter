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
ARG ENVIRONMENT
ARG NGINX_VERSION
ARG NGINX_ENVSUBST_TEMPLATE_SUFFIX=.template
ARG NGINX_ENVSUBST_TEMPLATE_DIR=/etc/nginx/templates
ARG LOCAL_SSL_KEY_FILE=./services/ssl/private.key
ARG LOCAL_SSL_CERT_FILE=./services/ssl/public.crt
ARG WEB_ROOT=/var/www/html/web
ARG WEB_HOSTNAME=localhost
ARG WEB_PORT=80

# Expose environment variables and set default value
# (these are available in containers created with this image)
ENV ENVIRONMENT=${ENVIRONMENT}
ENV NGINX_VERSION=${NGINX_VERSION}
ENV NGINX_ENVSUBST_TEMPLATE_SUFFIX=${NGINX_ENVSUBST_TEMPLATE_SUFFIX}
ENV NGINX_ENVSUBST_TEMPLATE_DIR=${NGINX_ENVSUBST_TEMPLATE_DIR}
ENV LOCAL_SSL_KEY_FILE=${LOCAL_SSL_KEY_FILE}
ENV LOCAL_SSL_CERT_FILE=${LOCAL_SSL_CERT_FILE}
ENV WEB_ROOT=$WEB_ROOT
ENV WEB_HOSTNAME=$WEB_HOSTNAME
ENV WEB_PORT=$WEB_PORT

# Copy localhost SSL certificate
COPY ${LOCAL_SSL_KEY_FILE} /etc/nginx/ssl/private.key
COPY ${LOCAL_SSL_CERT_FILE} /etc/nginx/ssl/public.crt

# Copy custom config template files into the container's `/etc/nginx/templates`
# directory, where they will be picked up by docker which will:
# - replace references to environment variables using `envsubst`
# - remove the `.template` extension
# - put the resulting config file in `/etc/nginx/conf.d`
COPY ./services/nginx/includes/*.conf${NGINX_ENVSUBST_TEMPLATE_SUFFIX} ${NGINX_ENVSUBST_TEMPLATE_DIR}/includes/
COPY ./services/nginx/*.conf${NGINX_ENVSUBST_TEMPLATE_SUFFIX} ${NGINX_ENVSUBST_TEMPLATE_DIR}/

# Copy custom NGINX config files into container
COPY ./services/nginx/includes/*.conf /etc/nginx/conf.d/includes/
COPY ./services/nginx/*.conf /etc/nginx/conf.d/

# Remove default config (we are using our own docker.conf file)
RUN rm /etc/nginx/conf.d/default.conf

# Copy public project files into nginx root directory
# and make php user 'www-data' the owner
COPY --chown=www-data:www-data ./web $WEB_ROOT
