# @see https://docs.docker.com/engine/reference/builder/

# Define build arguments available outside build stage (before first FROM instruction)
# @note value can be overriden by environment variable defined for this service in docker-compose.yml
#   or by environment variables defined when running the `docker build` command
ARG NODE_VERSION=16
ARG NODE_ENV=production

FROM node:$NODE_VERSION

# Make build arguments available in build stage (after FROM instruction)
# @see https://docs.docker.com/engine/reference/builder/#understand-how-arg-and-from-interact
ARG NODE_VERSION=$NODE_VERSION
ARG NODE_ENV=$NODE_ENV
ARG YARN_VERSION=1.16.0
ARG LOCAL_SSL_KEY_FILE=./services/httpd/ssl/localhost.selfsigned-private.key
ARG LOCAL_SSL_CERT_FILE=./services/httpd/ssl/localhost.selfsigned-public.cert
ARG ASSETS_DEVSERVER_PORT=3000

# Expose environment variables and set default value
# (these are available in containers created with this image)
ENV NODE_VERSION=$NODE_VERSION
ENV NODE_ENV=$NODE_ENV
ENV YARN_VERSION=$YARN_VERSION
ENV ASSETS_DEVSERVER_PORT=$ASSETS_DEVSERVER_PORT

# Other environment variables that are no ARGs but are used in the containers
ENV NPM_CONFIG_LOGLEVEL=info
ENV WEB_ROOT=/var/www/html
ENV WEB_HOSTNAME=localhost
ENV WEB_PORT=8443
ENV WEB_PORT_UNSECURE=8080
ENV ASSETS_DEVSERVER_HOSTNAME=$WEB_HOSTNAME
ENV ASSETS_DEVSERVER_HOSTNAME_INTERNAL=$ASSETS_DEVSERVER_HOSTNAME

# Set version for yarn (applies to all subdirectories of current working dir)
# @see https://github.com/nodejs/docker-node/blob/main/docs/BestPractices.md#upgradingdowngrading-yarn
RUN yarn policies set-version $YARN_VERSION

# Copy localhost SSL certificate
COPY --chown=node:node ${LOCAL_SSL_KEY_FILE} /home/node/ssl/private.key
COPY --chown=node:node ${LOCAL_SSL_CERT_FILE} /home/node/ssl/public.crt

# Set workdir to project root
WORKDIR /home/node/app

# Copy project files into container's node.js app directory
COPY --chown=node:node ./package.json ./
COPY --chown=node:node ./yarn.lock ./
COPY --chown=node:node ./assets ./assets
COPY --chown=node:node ./craftcms/templates ./craftcms/templates
# COPY --chown=node:node ./node_modules ./node_modules
# COPY --chown=node:node ./web/dist ./web/dist

# Prepare node_modules directory and make sure its writable
RUN mkdir node_modules && \
    chown -R node:node /home/node/app/

# Switch to non-root user to mitigate security issues
# @see https://github.com/nodejs/docker-node/blob/main/docs/BestPractices.md#non-root-user
# RUN usermod -d /home/www-data -l www-data node
USER node

# Expose ports on which the node-based assets server is listening
EXPOSE $ASSETS_DEVSERVER_PORT

# Install node dependencies with yarn
RUN yarn upgrade && yarn install --production=false

# When starting the image, install node modules with yarn and
# run vite dev server.
# @note running `yarn` first will install what's in yarn.lock or,
#   if the yarn.lock file is missing, update the dependencies and create it
# @todo Run 'yarn build' in production
CMD yarn install --production=false && yarn dev
