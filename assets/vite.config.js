/**
 * Config settings passed to vite.js to serve (development) and build (production) assets
 * @link https://vitejs.dev/config
 */

import path from 'path';
import fs from 'fs';
import { defineConfig } from 'vite'
import viteLegacy from '@vitejs/plugin-legacy';
import viteRestart from 'vite-plugin-restart';
import viteSvgIcons from 'vite-plugin-svg-icons';
import svgoConfig from './svgo.config.js';
import postcssConfig from './postcss.config.js';

export default defineConfig(function(ctx) {

  const rootPath = path.resolve(process.cwd(), './assets');
  const iconsPath = `${rootPath}/icons`;

  return {

    // Project root directory where index.html and source files are located
    // @see https://vitejs.dev/config/#root
    //
    // @accept {string} An absolute path, or a path relative to the config file

    root: rootPath,

    // Directory to serve as plain static assets. Files in this directory are
    // served at `/` during dev, and copied to the root of outDir during build.
    // They are always served or copied as-is without transform.
    // @see https://vitejs.dev/config/#publicdir
    //
    // @accept {string} An absolute path, or a path relative to the config file

    publicDir: `./public`,

    // base public path to which assets will be rewritten
    // when served in development or production
    // @see https://vitejs.dev/config/#base
    //
    // @accept {string} An absolute path, a path relative to the config file,
    //  or a full URL (e.g. https://foo.com/)
    base: (ctx.command == 'serve' ? '' : '/dist/'),

    // settings for production builds with rollup
    build: {
      outDir: `${process.env.WEB_ROOT}/dist`,
      emptyOutDir: true, // cleanup previously built files
      manifest: true, // generate manifest.json file
      rollupOptions: {
        input: { // key-value pairs for entrypoint scripts
          main: `${rootPath}/scripts/main.js`,
        },
        output: {
          sourcemap: true,
        },
      },
    },

    // settings for vitejs dev-server
    server: {
      // Dev-server host
      // host: process.env.ASSETS_DEVSERVER_HOSTNAME,
      host: '0.0.0.0',
      // Port the dev-server should listen to
      port: process.env.ASSETS_DEVSERVER_PORT,
      // Whether to stick to `server.port`, or try the next port if unavailable
      strictPort: true,
      // Enable https access using specific ssl certification keys
      https: {
        key: fs.readFileSync('/home/node/ssl/private.pem'),
        cert: fs.readFileSync('/home/node/ssl/public.pem'),
      },
      fs: {
        strict: true,
        allow: [ process.cwd() ],
      },
    },

    // Settings to resolve import paths
    resolve: {

      // Aliases used to resolve imported paths
      // @see https://github.com/rollup/plugins/tree/master/packages/alias#entries
      //
      // @accept {object} Where keys are aliases and values are absolute file-system paths or URLS
      alias: {
        '@icons': iconsPath,
        '@images': `${rootPath}/images`,
        '@styles': `${rootPath}/styles`,
        '@scripts': `${rootPath}/scripts`,
      }

    },

    // CSS importing options
    css: {
      postcss: postcssConfig,
    },

    // enable and configure vitejs plugins
    plugins: [

      // fixes static assets not being served from the build devserver
      // when using vite with a back-end application such as Craft-CMS or lavavel
      // @see https://nystudio107.com/docs/vite/#vite-processed-assets
      {
        name: 'static-asset-fixer',
        enforce: 'post',
        apply: 'serve',
        transform: (code, id) => {
          return {
            code: code.replace(
              /\/dist\/(.*)\.(svg|jp?g|png|gif|webp)/g,
              `${process.env.BUIDL_DEVSERVER_URL}/dist/$1.$2`
            ),
            map: null,
          }
        },
      },

      viteLegacy({
        // whether internal @babel/preset-env should autodetect the browserslist configs
        // @see https://github.com/browserslist/browserslist#browserslist-
        ignoreBrowserslistConfig: false,
      }),

      viteRestart({
        reload: [ // live-reload via dev-server when following files change
          '../craftcms/templates/**/*',
        ],
      }),

      viteSvgIcons({
        // icon folder with icons to include in cached sprite
        iconDirs: [ iconsPath ],
        // format to generate `symboldId` identifiers to <use>
        symbolId: 'icon-[dir]-[name]',
        svgoOptions: svgoConfig,
      }),
    ],

  };
});
