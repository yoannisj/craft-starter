<?php

// @see https://craftcms.com/docs/3.x/config/db-settings.html

use craft\helpers\App;

return [
    'driver' => App::env('DATABASE_DRIVER'),
    'server' => App::env('DATABASE_SERVER'),
    'port' => App::env('DATABASE_PORT'),
    'user' => App::env('DATABASE_USER'),
    'password' => App::env('DATABASE_PASSWORD'),
    'database' => App::env('DATABASE_NAME'),
    'schema' => App::env('DATABASE_SCHEMA'),
    'tablePrefix' => App::env('DATABASE_TABLE_PREFIX'),
];
