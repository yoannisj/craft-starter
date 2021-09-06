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

2. Duplicate the `.env.example` file, rename the copy to `.env`.

    ```
    cp .env.example .env
    ```

3. Choose a database driver by changing the `COMPOSE_FILE` variable in your `.env` file:

    - mysql: `COMPOSE_FILE=./docker-compose.yml:./services/database/mysql/docker-compose.override.yml`
    - mariadb: `COMPOSE_FILE=./docker-compose.yml:./services/database/mariadb/docker-compose.override.yml`
    - postgresql: `COMPOSE_FILE=./docker-compose.yml:./services/database/postgres/docker-compose.override.yml`

    **Note**: the database specific docker-compose file will also override some of the environment variables populated in the php containers, so Craft CMS knows which database driver to use.

4. Create a security key used for the project's crypotography, and assign it to the `SECURITY_KEY` environment variable. We recommend to use a random  alphanumeric string of 48 characters long.

5. Enable local HTTPS and virtual hostname (optional)

    - Choose a virtual hostname (e.g. `myproject.local`) and assign it `WEB_HOSTNAME` environment variable in your local `.env` file
    - Add the virtual hostname to your local computer's `hosts` file to point it to `127.0.0.1`
    - Create SSL certificate files for your virtual hostname with [mkcert](https://mkcert.dev/), or [openssl (self-signed)]() and save them in the project's `services/httpd/ssl` folder
    - Update the `LOCAL_SSL_KEY_FILE` and `LOCAL_SSL_CERT_FILE` environment variables in your local `.env` file to point to the newly created SSL files in `services/httpd/ssl`

6. Additionally to the environment variables edited above, you may want to override the following variables in the `.env` file to better reflect your project:

    - `PUBLIC_PORT_PREFIX` - Set this to a number between `1` and `5`, which will be used as a prefix to all ports exposed on your local computer by docker
    - `APP_ID` - Set this to a handle string that represents your craftcms project instance (e.g. `myproject_craftcms`)
    - `ENVIRONMENT` - Set this to `development` on your local computer so you can run the project in development mode
    - `DATABASE_NAME` - Set this to a string that represents your craftcms project instance (e.g. `myproject_craftcms`)
    - `DATABASE_TABLE_PREFIX` - Change this if your production database already contains data from another craft CMS project

7. Run `docker-compose run --rm craft composer update` to update all composer dependencies and update your project's `composer.lock` file.

8. Run `docker-compose run --rm craft php craft install` to install Craft in your local database

9. Run `docker-compose down && docker-compose up -d` to start the docker containers running your craft project locally

10. Visit http://localhost/admin in your browser and log in with the admin user account to start fiddling!

    **Note**: the local URL to your project differs if you enabled HTTPS and a virtual hostname in previous steps
    **Note**: the username and password to access the admin area are the ones created during craft installation in previous steps

## Development Environment

This project uses a docker as a development environment, relying on the docker-compose services described below.

The services use the project's own images, which are based on debian/ubuntu in order to minimise discrepancies with the production environment. The images' `Dockerfile`s and configuration files are saved in the project's `docker` repository.

- The `nginx` service provides the local web-server, used to serve static files from the `web` folder, and forwarding dynamic `.php` requests to the `craft` service.

- The `craft` service is responsible for handling dynamic web-requests with PHP-FPM, forwarded by nginx to Craft-CMS's `web/index.php` script file. Craft-CMS, its dependencies and plugins are installed on this service's image with Composer.

- The `craft-queue` service is responsible for running background jobs with the PHP CLI. It is built on the same image as `craft`, and therefore shares the same Composer and Craft-CMS installation. In order to discover and run jobs in Craft-CMS's queue, it runs the `craft queue/listen` command.

- The `mariadb` service provides a maria-db server, and is where Craft CMS's dynamic data is stored.

- The `redis` service provides a redis server, used to store Craft CMS's session and cache data in memory.

#### composer.json

- map the namespace of your custom craft modules to their base file path in the composer.json file's `autoload.psr-4` field
- add repositories to your custom composer packages to the `repositories` key

## TODO

- use docker secrets for passwords and other security/auth keys
- ensure depending services are also removed when running `docker-composer run --rm service_name`

## References

The following articles were used as references to build this project, and contain further information about the project's setup and practices:

- https://mattgrayisok.com/a-craft-cms-development-workflow-with-docker-part-1-local-development
- https://mattgrayisok.com/logging-from-craft-cms-in-docker 
- https://mattgrayisok.com/craft-cms-in-docker-https-ftw
- https://nystudio107.com/blog/an-annotated-docker-config-for-frontend-web-development
- https://blog.amosti.net/local-reverse-proxy-with-nginx-mkcert-and-docker-compose/
