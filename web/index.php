<?php

/**
 * Craft web bootstrap file
 */

// Set PHP constants used by Craft-CMS
// @see https://craftcms.com/docs/3.x/config/#php-constants
define('CRAFT_BASE_PATH', dirname(__DIR__).'/craftcms');
define('CRAFT_COMPOSER_PATH', dirname(__DIR__).'/composer.json');
define('CRAFT_VENDOR_PATH', dirname(__DIR__).'/vendor');

// Load Composer's autoloader
require_once CRAFT_VENDOR_PATH.'/autoload.php';

// Load environment variables with dotenv?
if (
    ($useDotEnv = getenv('USE_PHP_DOTENV') ?: true)
    && class_exists('Dotenv\Dotenv')
    && file_exists(($dotEnvPath = dirname(__DIR__)).'/.env')
) {
    (Dotenv\Dotenv::create($dotEnvPath))->load();
}

define('CRAFT_ENVIRONMENT', getenv('ENVIRONMENT') ?: 'production');
define('CRAFT_LOG_PHP_ERRORS', getenv('CRAFT_LOG_PHP_ERRORS') ?: false);

// Since we are using docker for development, we want to stream logs to the terminal output
// if (CRAFT_ENVIRONMENT == 'development') {
//     define('CRAFT_STREAM_LOG', true);
// }

// Load and run Craft
$app = require CRAFT_VENDOR_PATH.'/craftcms/cms/bootstrap/web.php';
$app->run();

