const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);

// Copiar librerías de firmas desde node_modules a public/js
mix.copy('node_modules/pdf-lib/dist/pdf-lib.min.js', 'public/js/pdf-lib.min.js')
   .copy('node_modules/signature_pad/dist/signature_pad.umd.min.js', 'public/js/signature_pad.min.js');