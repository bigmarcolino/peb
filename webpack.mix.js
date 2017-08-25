const { mix } = require('laravel-mix');

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
   .sass('resources/assets/sass/app.scss', 'public/css')
   .copy('node_modules/jquery', 'public/node_modules/jquery')
   .copy('node_modules/angular', 'public/node_modules/angular')
   .copy('node_modules/angular-animate', 'public/node_modules/angular-animate')
   .copy('node_modules/moment', 'public/node_modules/moment')
   .copy('node_modules/angular-spinner', 'public/node_modules/angular-spinner')
   .copy('node_modules/angular-tooltips', 'public/node_modules/angular-tooltips')
   .copy('node_modules/angular-eonasdan-datetimepicker', 'public/node_modules/angular-eonasdan-datetimepicker')
   .copy('node_modules/angular-ui-bootstrap', 'public/node_modules/angular-ui-bootstrap')
   .copy('node_modules/ng-image-gallery', 'public/node_modules/ng-image-gallery')
   .copy('node_modules/angular-image-compress', 'public/node_modules/angular-image-compress');
