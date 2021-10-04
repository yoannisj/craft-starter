<?php

use craft\helpers\App as AppHelper;

$publicPortPrefix = AppHelper::env('PUBLIC_PORT_PREFIX') ?: '';
$devServerHost = AppHelper::env('ASSETS_DEVSERVER_HOSTNAME') ?: AppHelper::env('WEB_HOSTNAME');
$devServerHostInternal = AppHelper::env('ASSETS_DEVSERVER_HOSTNAME_INTERNAL') ?: $devServerHost;
$devServerPort = AppHelper::env('ASSETS_DEVSERVER_PORT') ?: 3000;

$devServerPublic = 'https://'.$devServerHost.':'.$publicPortPrefix.$devServerPort;
$devServerInternal = 'https://'.$devServerHostInternal.':'.$devServerPort;

return [

    '*' => [

        /**
         * @var bool Whether we should use Vite's dev server to load script modules
         *  and leverage HMR for Js, CSS and even Twig snippets
         *
         * @accept bool
         * @default \craft\helpers\App::env('DEV_MODE')
         */

        'useDevServer' => false,

        /**
         * @var string File system path (or URL) to the Vite-built manifest.json file.
         *
         * @accept string A full URL, a relative or absolute URL path, or a Yii2 alias.
         * @required
         */

        'manifestPath' => '@webroot/dist/manifest.json',

        /**
         * @var string the public server URL to your asset files.
         * @note This will be used as base URL in `<script>` tags on the frontend
         *  for production builds.
         *
         * @accept string A full URL, a relative or absolute URL path, or a Yii2 alias.
         * @required
         */

        'serverPublic' => '@web/dist/',

    ],

    'development' => [

        /**
         * @var bool Whether we should use Vite's dev server to load script modules
         *  and leverage HMR for Js, CSS and even Twig snippets
         *
         * @accept bool
         * @default false
         */

        'useDevServer' => true,

        /**
         * @var string Public URL to Vite's dev server used for HMR.
         * @note This will be used as base URL in `<script>` tags on the frontend
         *  during development
         *
         * @accept string A full URL, a relative or absolute URL path, or a Yii2 alias.
         * @required If `useDevServer` is set to `true`
         */

        'devServerPublic' => $devServerPublic,

        /**
         * @var bool Whether to check if the dev-server is availabel by pinging it
         *
         * @default false
         */

        'checkDevServer' => true,

        /**
         * @var string URL to dev-server from environment where PHP is executed
         * in a VM or containerized setup.
         * @note even if you are using SSL, this always needs to be a `http://` URL
         *
         * @accept string A full URL to the dev server
         * @ignored If checkDevServer is `false`
         */

        'devServerInternal' => $devServerInternal,

        /**
         * @var string Path to the vitejs entry file (or *a* vitejs entry file, really?)
         * to inject on error pages.
         * @note Setting this will allow live-reloading (via the `vite-plugin-restart`
         * vitejs plugin) out of a Twig error once it is fixed in the template
         *
         * @accept String Path to vitejs entry file source
         * @default null
         */

        'errorEntry' => 'assets/scripts/main.js',

    ],

];
