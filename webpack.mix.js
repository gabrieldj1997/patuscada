const mix = require('laravel-mix');

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

mix.js([
        'resources/js/app.js'
    ], 'public/js/app.js')
    .js([
        'resources/js/chat/chat.js'
    ], 'public/js/chat.js').
    js([
        'resources/js/game/jogo.js'
    ], 'public/js/jogo.js')
    .styles([
        'resources/css/app.css',
        'resources/css/chat.css',
    ], 'public/css/style.css')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps()
    .version();
