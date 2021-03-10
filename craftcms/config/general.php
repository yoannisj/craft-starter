<?php

// @see https://craftcms.com/docs/3.x/config/config-settings.html

use craft\helpers\App;

return [

    // settings shared accross all environments
    '*' => [

        // Whether generated URLs should omit index.php
        // @allows bool
        // @default false

        'omitScriptNameInUrls' => true,

        // Any custom Yii aliases (opens new window) that should be defined for
        // every request.
        // @allows string[]
        // @default []

        'aliases' => [
            'craftWebUrl' => App::env('CRAFT_WEB_URL'),
            'localUploads' => App::env('CRAFT_LOCAL_UPLOADS_PATH'),
            'localUploadsUrl' => App::env('CRAFT_LOCAL_UPLOADS_URL'),
        ],

        // Whether uploaded filenames with non-ASCII characters should be
        // converted to ASCII (i.e. ñ → n).
        // @allows bool
        // @default false

        'convertFilenamesToAscii' => false,

        // The maximum upload file size allowed.
        // @see https://docs.craftcms.com/api/v3/craft-helpers-confighelper.html#public-methods
        // @allows int|string
        // @default 16777216 (16MB)

        'maxUploadFileSize' => 6000000, // (6MB)

        // The default length of time Craft will store data, RSS feed, and
        // template caches. If set to 0, data and RSS feed caches will be stored
        // indefinitely; template caches will be stored for one year.
        // @see https://docs.craftcms.com/api/v3/craft-helpers-confighelper.html#public-methods
        // @allows int|string|DateInterval|empty
        // @default 86400 (1 day)

        'cacheDuration' => 86400,

        // Whether users should be allowed to create similarly-named tags.
        // @allows bool
        // @default true

        'allowSimilarTags' => false,

        // Whether Craft should run pending queue jobs automatically when someone visits the control panel.
        // If disabled, an alternate queue worker must be set up separately, either as an always-running daemon
        // (opens new window), or a cron job that runs the `queue/run` command every minute:
        //  `* * * * * /path/to/project/craft queue/run`
        // @allows bool
        // @default true

        'runQueueAutomatically' => false,
    ],

    // override settings for development environment
    'development' => [

        // Whether the system should run in Dev Mode
        // @see https://craftcms.com/support/dev-mode
        // @allows bool
        // @default false

        'devMode' => true,

        // Whether front end requests should respond with `X-Robots-Tag: none`
        // HTTP headers, indicating that pages should not be indexed, and links
        // on the page should not be followed, by web crawlers.
        // @allows bool
        // @default false

        'disallowRobots' => true,

        // Whether to enable Craft’s template {% cache %} tag on a global basis.
        // @allows bool
        // @default true

        'enableTemplateCaching' => false,

        // Whether Craft should cache GraphQL queries.
        // @allows bool
        // @default true

        'enableGraphQlCaching' => false,
    ],

    // override settings for staging environment
    'staging' => [

        // Whether front end requests should respond with `X-Robots-Tag: none`
        // HTTP headers, indicating that pages should not be indexed, and links
        // on the page should not be followed, by web crawlers.
        // @allows bool
        // @default false

        'disallowRobots' => true,

        // Whether admins should be allowed to make administrative changes to
        // @see https://craftcms.com/docs/3.x/project-config.html#environment-setup
        // the system.
        // @allows bool
        // @default true

        'allowAdminChanges' => false,

        // Whether Craft should allow system and plugin updates in the control
        // panel, and plugin installation from the Plugin Store.
        // @allows bool
        // @default true

        'allowUpdates' => false,
    ],

    // override settings for production environment
    'production' => [

        // Whether admins should be allowed to make administrative changes to
        // @see https://craftcms.com/docs/3.x/project-config.html#environment-setup
        // the system.
        // @allows bool
        // @default true

        'allowAdminChanges' => false,

        // Whether Craft should allow system and plugin updates in the control
        // panel, and plugin installation from the Plugin Store.
        // @allows bool
        // @default true

        'allowUpdates' => false,
    ],


];
