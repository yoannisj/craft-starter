# Craft Starter
Boilerplate for Craft CMS projects with Docker as development environment

## Getting Started

This repository is to be used to start a new craft project:

1. Copy the repository's contents into a local directory, and `cd` into the local repository path

    - Using [degit](https://github.com/Rich-Harris/degit#readme) (recommended):
    ```
    degit github:yoannisj/craft-starter <path/to/project-directory>
    cd <path/to/project-directory>
    ```

    - Using git clone: 
    ```
    git clone --depth=1 https://github.com/yoannisj/craft-starter.git <path/to/project-directory>
    cd <path/to/project-directory>
    ```

2. Duplicate the `.env.example` file and rename your copy to `.env`.

    ```
    cp .env.example .env
    ```

3. Choose a database driver by changing the `COMPOSE_FILE` variable in your `.env` file:

    - mysql: `COMPOSE_FILE=./docker-compose.yml:./services/database/mysql/docker-compose.override.yml`
    - mariadb: `COMPOSE_FILE=./docker-compose.yml:./services/database/mariadb/docker-compose.override.yml`
    - postgresql: `COMPOSE_FILE=./docker-compose.yml:./services/database/postgres/docker-compose.override.yml`

    **Note**: the database specific docker-compose file will also override some of the environment variables populated in the php containers, so Craft CMS knows which database driver to use.

4. Create a security key used for the project's crypotography, and assign it to the `SECURITY_KEY` environment variable.

    **Note**: the security key must be shared on development, staging and production environments, so make sure you use a strong key from the start. We recommend to use a random  alphanumeric string of 48 characters long, which you can generate using a a service such as https://passwordsgenerator.net/.

5. Enable local HTTPS and virtual hostname (optional)

    - Choose a virtual hostname (e.g. `myproject.test`) and assign it `WEB_HOSTNAME` environment variable in your local `.env` file
    - Add the virtual hostname to your local computer's `hosts` file and point it to `127.0.0.1`
    - Create SSL certificate files for your virtual hostname with [mkcert](./docs/dev/ssl-mkcert-certificates.md), or [openssl (self-signed)](./docs/dev/ssl-openssl-certificates.md)
    - Update the `LOCAL_SSL_KEY_FILE` and `LOCAL_SSL_CERT_FILE` environment variables in your local `.env` file to point to the newly created SSL files in `services/ssl`

6. Additionally to the environment variables edited above, you may want to override the following variables in the `.env` file to better reflect your project:

    - `PUBLIC_PORT_PREFIX` - Set this to a number between `1` and `5`, which will be used as a prefix to all ports exposed on your local computer by docker
    - `APP_ID` - Set this to a handle string that represents your craftcms project instance (e.g. `myproject_craftcms`)
    - `ENVIRONMENT` - Set this to `development` on your local computer so you can run the project in development mode
    - `DATABASE_NAME` - Set this to a string that represents your craftcms project instance (e.g. `myproject_craftcms_localdev`)
    - `DATABASE_TABLE_PREFIX` - Change this if your production database already contains data from another craft CMS project

7. Run `docker-compose run --rm craft-web composer update` to update all composer dependencies and update your project's `composer.lock` file.

8. Run `docker-compose run --rm craft-cli install` to install Craft in your local database

9. Run `docker-compose down && docker-compose up -d` to start the docker containers running your craft project locally

10. Visit http://localhost:8080/admin in your browser and log in with the admin user account to start fiddling!

    **Note**: the local URL to your project differs if you enabled HTTPS and a virtual hostname in previous steps
    **Note**: the username and password to access the admin area are the ones you created during craft installation in previous steps

## Development Environment

This project uses docker as a development environment, relying on the docker-compose services described below.

The services use the project's own images, which are based on debian/ubuntu in order to minimise discrepancies with the production environment. The images' `Dockerfile`s and configuration files are saved in the project's `docker` repository.

- The `httpd` service provides the local web-server, used to serve static files from the project's `web` folder, and forwarding dynamic `.php` requests to the `craft-web` service.

- The `craft-web` service is responsible for handling dynamic web-requests with PHP-FPM, forwarded by the httpd web-server to Craft-CMS's `web/index.php` script file. Craft-CMS, its dependencies and plugins are installed on this service's image with Composer.

- The `craft-cli` service can be used to run commands from Craft's CLI. It is using the same docker image as the `craft-web` service, and runs commands in the same Craft-CMS instance. To run a Craft CLI command, use `docker-compose run --rm craft-cli <command-name>`.

- The `craft-queue` service is responsible for running background jobs with the PHP CLI. It is using the same docker image as `craft-web`, and therefore runs jobs from the same Craft-CMS instance. In order to discover and run jobs in Craft-CMS's queue, it runs the `craft queue/listen` command behind the scenes.

- The `db` service provides a maria-db server, and is where Craft CMS's dynamic data is stored.

- The `redis` service provides a redis server, used to store Craft CMS's session and cache data in memory for an extra performance boost.

- The `ngrok` service can be used to expose your project's local web-server publicly with [ngrok](https://ngrok.com/), and share previews that can be accessed by co-workers on their own devices, or by external web-services (e.g. services that need to call craft controller action URLs).

#### composer.json

- map the namespace of your custom craft modules to their base file path in the composer.json file's `autoload.psr-4` field
- add repositories to your custom composer packages to the `repositories` key

## TODO

- add asset compilation and bundling service (using gulp.js, vite.js or laravel mix)
- separate environment variables into service-speficic .env files (e.g. `.craft.env`, `.db.env`, etc.)
- move docs to the `docs/dev` sub-folder and reserve the Readme.md file for the project itself
- use docker secrets for passwords and other cryptographic keys for encryption/authentication
- ensure depending services are also removed when running `docker-composer run --rm service_name`
- further abstract down the `ngrok` service to support other web-tunneling services
- implement git deployment to runcloud and/or laravel-forge with github actions
- document installation and deployment on runcloud/laravel-forge servers

## References

The following articles were used as references to build this project, and contain further information about the project's setup and practices:

- https://mattgrayisok.com/a-craft-cms-development-workflow-with-docker-part-1-local-development
- https://mattgrayisok.com/logging-from-craft-cms-in-docker 
- https://mattgrayisok.com/craft-cms-in-docker-https-ftw
- https://nystudio107.com/blog/an-annotated-docker-config-for-frontend-web-development
- https://blog.amosti.net/local-reverse-proxy-with-nginx-mkcert-and-docker-compose/
