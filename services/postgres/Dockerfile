# @see https://docs.docker.com/engine/reference/builder/
# @see https://hub.docker.com/_/postgres

# Define build arguments available outside build stage (before first FROM instruction)
# @note value can be overriden by environment variable defined for this service in docker-compose.yml
#   or by environment variables defined when running the `docker build` command
ARG POSTGRES_VERSION=13.3
ARG ENVIRONMENT=production

# Base this image on corresponding version of official redis image
# @see https://hub.docker.com/_/redis
FROM postgres:${POSTGRES_VERSION}

# Make build arguments available in build stage (after FROM instruction)
# @see https://docs.docker.com/engine/reference/builder/#understand-how-arg-and-from-interact
ARG POSTGRES_VERSION
ARG ENVIRONMENT

# Expose environment variables and set default value
# (these are available in containers created with this image)
ENV POSTGRES_VERSION=${POSTGRES_VERSION}
ENV ENVIRONMENT=${ENVIRONMENT}
