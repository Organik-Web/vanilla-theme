const mix = require('laravel-mix');
const path = require('path');
const rootPath = path.resolve(__dirname, '..');

mix.setPublicPath(rootPath);

// Compile stylesheets
mix
    .sass('../css/main.scss', '../css/main.min.css')
    .sass('../css/login.scss', '../css/login.min.css')
    .sass('../css/admin.scss', '../css/admin.min.css')
    .sass('../css/editor.scss', '../css/editor.min.css')

    .js('../js/libs/modaal.js', '../js/libs/modaal.min.js')
    .js('../js/libs/splide.js', '../js/libs/splide.min.js')
    .js('../js/libs/what-input.js', '../js/libs/what-input.min.js')
    .js('../js/orgnk-core-js/orgnk-core.js', '../js/orgnk-core-js/orgnk-core.min.js')
    .js('../js/block-editor.js', '../js/block-editor.min.js')
    .js('../js/extras.js', '../js/extras.min.js')

    .options({
        processCssUrls: false,
        terser: {
            extractComments: false,
        },
    })
    .version();