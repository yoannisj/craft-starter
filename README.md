# Craft Starter
Boilerplate for Craft CMS projects with Docker as development environment

## Getting Started

This repository is to be used to start a new craft project:

1. Clone this repository locally and `cd` into the local repository path

    ```
    git clone https://github.com/yoannisj/craft-starter.git <project-repository-name>
    cd path/to/<project-repository-name>
    ```

2. Duplicate the `.env.example` file, rename the copy to `.env`.

    ```
    cp .env.example .env
    ```

3. Choose a database driver by changing the `COMPOSE_FILE` variable in your `.env` file:

    - mysql: `COMPOSE_FILE=./docker-compose.yml:./services/mysql/docker-compose.override.yml`
    - mariadb: `COMPOSE_FILE=./docker-compose.yml:./services/mariadb/docker-compose.override.yml`
    - postgresql: `COMPOSE_FILE=./docker-compose.yml:./services/postgres/docker-compose.override.yml`

    **Note**: the database specific docker-compose file will also override some of the environment variables populated in the php containers, so Craft CMS knows which database driver to use.

4. Set the environment variables in the new `.env` file to your will using your favourite text editor

    You probably want to change the following environment variables
    
    `ENVIRONMENT` - Set this to `development` on your local computer so you can run the project in development mode
    `DATABASE_NAME` - Set this to the name of the database that you will use on production
    `DATABASE_TABLE_PREFIX` - Change this if your production database already contains another craft project using the same table prefix

4. Run `docker-compose run --rm craft composer update` to update all composer dependencies and write your `composer.lock` file.

5. Run `docker-compose run --rm craft php craft install` to install Craft in your local database

6. Run `docker-compose down && docker-compose up -d` to start the docker containers running your craft project locally

7. Visit http://localhost/admin in your browser and log in with the admin user account (created during craft installation to start in step 5) to start fiddling!


## Development Environment

This project uses a docker as a development environment, relying on the docker-compose services described below.

The services use the project's own images, which are based on debian/ubuntu in order to minimise discrepancies with the production environment. The images' `Dockerfile`s and configuration files are saved in the project's `docker` repository.

- The `nginx` service provides the local web-server, used to serve static files from the `web` folder, and forwarding dynamic `.php` requests to the `craft` service.

- The `craft` service is responsible for handling dynamic web-requests with PHP-FPM, forwarded by nginx to Craft-CMS's `web/index.php` script file. Craft-CMS, its dependencies and plugins are installed on this service's image with Composer.

- The `craft-queue` service is responsible for running background jobs with the PHP CLI. It is built on the same image as `craft`, and therefore shares the same Composer and Craft-CMS installation. In order to discover and run jobs in Craft-CMS's queue, it runs the `craft queue/listen` command.

- The `mariadb` service provides a maria-db server, and is where Craft CMS's dynamic data is stored.

- The `redis` service provides a redis server, used to store Craft CMS's session and cache data in memory.

#### composer.json

- sets `"mininum-stablity"` key to `"dev"` so we can install custom packages from a git repository branch directly (without having to release a version).
- map the namespace of your custom craft modules to their base file path in the `"autoload" > "psr-4"` key
- add repositories to your custom composer packages to the `"repositories"` key

## TODO

- ensure depending services are also removed when running `docker-composer run --rm service_name`
- implement trusted SSL certificates for https://localhost with mkcert:
    @see https://blog.amosti.net/local-reverse-proxy-with-nginx-mkcert-and-docker-compose/

## References

The following articles were used as references to build this project, and contain further information about the project's setup and practices:

- https://mattgrayisok.com/a-craft-cms-development-workflow-with-docker-part-1-local-development
- https://mattgrayisok.com/logging-from-craft-cms-in-docker 
- https://mattgrayisok.com/craft-cms-in-docker-https-ftw
- https://nystudio107.com/blog/an-annotated-docker-config-for-frontend-web-development
- https://blog.amosti.net/local-reverse-proxy-with-nginx-mkcert-and-docker-compose/
