/**
 * @warn postcss-load-config (used internally by vite.js) does not
 *  support config as ES6 module
 */

 module.exports = {
  plugins: [
    require('postcss-normalize')(),
    require('autoprefixer')(),
    require('postcss-flexbugs-fixes')(),
  ],
};
