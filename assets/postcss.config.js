/**
 * @warn postcss-load-config (used internally by vite.js) does not
 *  support config as ES6 module
 */

 module.exports = {
  plugins: [
    require('autoprefixer')(),
    require('postcss-normalize')(),
    require('postcss-flexbugs-fixes')(),
  ],
};
