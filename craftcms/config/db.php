<?php

// @see https://craftcms.com/docs/3.x/config/db-settings.html

return [
    'driver' => 'mysql',
    'server' => getenv('DATABASE_SERVER'),
    'port' => getenv('DATABASE_PORT'),
    'user' => getenv('DATABASE_USER'),
    'password' => getenv('DATABASE_PASSWORD'),
    'database' => getenv('DATABASE_NAME'),
    'schema' => getenv('DATABASE_SCHEMA'),
    'tablePrefix' => getenv('DATABASE_TABLE_PREFIX'),
];
