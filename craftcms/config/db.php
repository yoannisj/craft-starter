<?php

// @see https://craftcms.com/docs/3.x/config/db-settings.html

use craft\helpers\App;

$dbDriver = App::env('DATABASE_DRIVER');
$dbPort = App::env('DATABASE_PORT');

if ($dbDriver == 'mariadb') {
    $dbDriver = 'mysql';
} else if ($dbDriver == 'postgres') {
    $dbDriver = 'pgsql';
}

if (empty($dbPort))
{
    if ($dbDriver == 'mysql') {
        $dbPort = 3306;
    } else if ($dbDriver == 'pgsql') {
        $dbPort = 5432;
    }
}

return [
    'driver' => $dbDriver,
    'server' => App::env('DATABASE_HOST'),
    'port' => $dbPort,
    'user' => App::env('DATABASE_USER'),
    'password' => App::env('DATABASE_PASSWORD'),
    'database' => App::env('DATABASE_NAME'),
    'schema' => App::env('DATABASE_SCHEMA'),
    'tablePrefix' => App::env('DATABASE_TABLE_PREFIX'),
];
