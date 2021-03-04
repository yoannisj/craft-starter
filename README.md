# Craft Starter
Boilerplate for Craft CMS projects with Docker as development environment

## Usage

This repository is to be used to start a new craft project:

1. Clone this repository and `cd` into the local repository path
2. Run `docker-compose run --rm php-web composer update` to update all composer dependencies and write your composer.lock file.
3. Run `docker-compose run --rm php-web ./craft install` to install Craft in your local database

## Development Environment

This project uses a docker as a development environment, relying on the docker-compose services described below.

The services use the project's own images, which are based on debian/ubuntu in order to minimise discrepancies with the production environment. The images' `Dockerfile`s and configuration files are saved in the project's `docker` repository.

- The `nginx` service provides the local web-server, used to serve static files from the `web` folder, and forwarding dynamic `.php` requests to the `php-web` service.

- The `php-web` service is responsible for handling dynamic web-requests with PHP-FPM, forwarded by nginx to Craft-CMS's `web/index.php` script file. Craft-CMS, its dependencies and plugins are installed on this service's image with Composer.

- The `php-worker` service is responsible for running background jobs with the PHP CLI. It is built on the same image as `php-web`, and therefore shares the same Composer and Craft-CMS installation. In order to discover and run jobs in Craft-CMS's queue, it runs the `craft queue/listen` command.

- The `mariadb` service provides a maria-db server, and is where Craft CMS's dynamic data is stored.

- The `redis` service provides a redis server, used to store Craft CMS's session and cache data in memory.

#### composer.json

- sets `"mininum-stablity"` key to `"dev"` so we can install custom packages from a git repository branch directly (without having to release a version).
- map the namespace of your custom craft modules to their base file path in the `"autoload" > "psr-4"` key
- add repositories to your custom composer packages to the `"repositories"` key

## References

The following articles were used as references to built this project, and contain further information about the project's setup and practices:

- https://mattgrayisok.com/a-craft-cms-development-workflow-with-docker-part-1-local-development
- https://mattgrayisok.com/logging-from-craft-cms-in-docker 
- https://mattgrayisok.com/craft-cms-in-docker-https-ftw
- https://nystudio107.com/blog/an-annotated-docker-config-for-frontend-web-development
