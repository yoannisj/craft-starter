# @see https://docs.docker.com/engine/reference/builder/
# @see https://hub.docker.com/_/redis

# Define build arguments available outside build stage (before first FROM instruction)
# @note value can be overriden by environment variable defined for this service in docker-compose.yml
#   or by environment variables defined when running the `docker build` command
ARG REDIS_VERSION=5
ARG ENVIRONMENT=production

# Base this image on corresponding version of official redis image
# @see https://hub.docker.com/_/redis
FROM redis:${REDIS_VERSION}

# Make build arguments available in build stage (after FROM instruction)
# @see https://docs.docker.com/engine/reference/builder/#understand-how-arg-and-from-interact
ARG REDIS_VERSION
ARG ENVIRONMENT

# Expose NGINX_VERSION as environment variable (available in containers created with this image)
ENV REDIS_VERSION=${REDIS_VERSION}
ENV ENVIRONMENT=${ENVIRONMENT}
