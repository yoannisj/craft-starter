<?php

// @see https://craftcms.com/docs/3.x/config/#application-configuration

use craft\helpers\App;

$environment = App::env('ENVIRONMENT');
$appId = App::env('APP_ID');

$redisConfig = [
    'class' => yii\redis\Connection::class,
    'hostname' => App::env('REDIS_HOST'),
    'port' => App::env('REDIS_PORT'),
];

$redisPassword = App::env('REDIS_PASSWORD');
if (!empty($redisPassword)) {
    $redisConfig['password'] = $redisPassword;
}

return [

    '*' => [
        'components' => [
            'redis' => $redisConfig,
            'cache' => [
                'class' => yii\redis\Cache::class,
                // Use a separate redis database for the cache component
                'redis' => array_merge($redisConfig, [
                    'database' => 0,
                ]),
                'defaultDuration' => 86400, // (in seconds => 1day)
                'keyPrefix' => $appId.'_'.$environment,
            ],
            'session' => function() use ($redisConfig)
            {
                // Get the default component config
                $config = App::sessionConfig();

                // Override the class to use redis' session class
                $config['class'] = yii\redis\Session::class;
                // Use a separate redis database for the session component
                $config['redis'] = array_merge($redisConfig, [
                    'database' => 1,
                ]);

                // Instantiate and return the custom component
                return Craft::createObject($config);
            },
        ],
    ],

];
