/**
 * Options passed to svgo to compress svg images
 * @link https://github.com/svg/svgo#configuration
 */

 module.exports = {
  plugins: [
    'cleanupAttrs',
    'removeXMLProcInst',
    'removeDoctype',
    'removeComments',
    'removeMetadata',
    // 'removeTitle',
    'removeDesc',
    'removeUselessDefs',
    // 'removeXMLNS',
    'removeEditorsNSData',
    'removeEmptyAttrs',
    'removeHiddenElems',
    'removeEmptyText',
    'removeEmptyContainers',
    // 'removeViewBox',
    'cleanupEnableBackground',
    'minifyStyles',
    'convertStyleToAttrs',
    'convertColors',
    'convertPathData',
    'convertTransform',
    'removeUnknownsAndDefaults',
    'removeNonInheritableGroupAttrs',
    'removeUselessStrokeAndFill',
    'removeUnusedNS',
    // 'cleanupIDs',
    'cleanupNumericValues',
    'cleanupListOfValues',
    'moveElemsAttrsToGroup',
    // 'moveGroupAttrsToElems',
    'collapseGroups',
    // 'removeRasterImages',
    'mergePaths',
    'convertShapeToPath',
    'removeDimensions',
    {
      name: 'removeAttrs',
      params: {
        attrs: '(stroke|fill|style|class)'
      }
    },
  ]
};
