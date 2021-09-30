<?php

// @see https://craftcms.com/docs/3.x/config/#application-configuration

use craft\helpers\App;

// Include environment in APP_ID to support sharing services for multiple
// environments (e.g. staging and production running on 1 VPS)
$environment = App::env('ENVIRONMENT');
$appId = (App::env('APP_ID') ?: 'CraftCMS').'_'.$environment;

$redisConfig = [
    'class' => yii\redis\Connection::class,
    'hostname' => App::env('REDIS_HOSTNAME'),
    'port' => App::env('REDIS_PORT'),
];

$redisPassword = App::env('REDIS_PASSWORD');
if (!empty($redisPassword)) {
    $redisConfig['password'] = $redisPassword;
}

return [

    '*' => [
        'id' => $appId,
        'components' => [
            'redis' => $redisConfig,
            'cache' => [
                'class' => yii\redis\Cache::class,
                // Use a separate redis database for the cache component
                'redis' => array_merge($redisConfig, [
                    'database' => 0,
                ]),
                'defaultDuration' => 86400, // (in seconds => 1day)
                'keyPrefix' => $appId,
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
        'modules' => [
            // Register and configure custom craft modules here.
            // For example:
            // 'my-custom-module' => [
            //     'class' => \modules\my-custom-module\src\MyCustomModule::class,
            //     'propertyName' => propertyValue
            // ]
        ],
        'bootstrap' => [
            // list of modules that need to be loaded automatically by Craft
            // For example:
            // 'my-custom-module', 'another-custom-module'
        ]
    ],

];
