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

mix
    .js('resources/assets/js/app.js', 'public/js')
    .extract(['bootstrap', 'jquery'])
    .sass('resources/assets/sass/app.scss', 'public/css')
    .copyDirectory('node_modules/simplemde/dist', 'public/vendor/simplemde')
    .copyDirectory('node_modules/bootstrap-tagsinput/dist', 'public/vendor/bootstrap-tagsinput')
    .copyDirectory('node_modules/sweetalert/dist', 'public/vendor/sweetalert')
    .copyDirectory('node_modules/inline-attachment/src', 'public/vendor/inline-attachment')
    .copyDirectory('node_modules/pc-bootstrap4-datetimepicker/build', 'public/vendor/pc-bootstrap4-datetimepicker')
    .copyDirectory('node_modules/moment/min', 'public/vendor/moment')
    .minify([
        'public/vendor/sweetalert/sweetalert.css',
        'public/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css',
        'public/vendor/inline-attachment/inline-attachment.js',
        'public/vendor/inline-attachment/codemirror-4.inline-attachment.js',
    ])
    .version([
        'public/vendor/sweetalert/sweetalert.min.js',
        'public/vendor/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'public/vendor/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'public/vendor/moment/moment-with-locales.min.js',
        'public/vendor/pc-bootstrap4-datetimepicker/css/bootstrap-datetimepicker.min.css',
        'public/vendor/pc-bootstrap4-datetimepicker/js/bootstrap-datetimepicker.min.js',
        'public/vendor/simplemde/simplemde.min.css',
        'public/vendor/simplemde/simplemde.min.js',
        'public/vendor/sweetalert/sweetalert.min.js',
    ]);
