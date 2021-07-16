# @see https://docs.docker.com/engine/reference/builder/
# @see https://hub.docker.com/_/mysql

# Define build arguments available outside build stage (before first FROM instruction)
# @note value can be overriden by environment variable defined for this service in docker-compose.yml
#   or by environment variables defined when running the `docker build` command
ARG MYSQL_VERSION=8
ARG ENVIRONMENT=production

# Base this image on corresponding version of official redis image
# @see https://hub.docker.com/_/redis
FROM mysql:${MYSQL_VERSION}

# Make build arguments available in build stage (after FROM instruction)
# @see https://docs.docker.com/engine/reference/builder/#understand-how-arg-and-from-interact
ARG MYSQL_VERSION
ARG ENVIRONMENT

# Expose environment variables and set default value
# (these are available in containers created with this image)
ENV MYSQL_VERSION=${MYSQL_VERSION}
ENV ENVIRONMENT=${ENVIRONMENT}
