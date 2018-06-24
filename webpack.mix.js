let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css', {
     importer: function(url, prev) {
       if(url.indexOf('@material') === 0) {
         var filePath = url.split('@material')[1];
         var nodeModulePath = './node_modules/@material/' + filePath;
         return { file: require('path').resolve(nodeModulePath) };
       }

       if(url.indexOf('material-components-web') === 0) {
         var nodeModulePath = './node_modules/material-components-web/material-components-web';
         return { file: require('path').resolve(nodeModulePath) };
       }

       if (url.indexOf('~') === 0) {
         var filePath = url.split('~')[1];
         var nodeModulePath = './node_modules/' + filePath;
         return { file: require('path').resolve(nodeModulePath) };
       }

       return { file: url };
     }
   });
