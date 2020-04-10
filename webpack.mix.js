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

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

const VueLoaderPlugin = require('vue-loader/lib/plugin');
module.exports = {
    // ...
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    transformAssetUrls: {
                        video: ['src', 'poster'],
                        source: 'src',
                        img: 'src',
                        image: 'xlink:href',
                        'b-avatar': 'src',
                        'b-img': 'src',
                        'b-img-lazy': ['src', 'blank-src'],
                        'b-card': 'img-src',
                        'b-card-img': 'src',
                        'b-card-img-lazy': ['src', 'blank-src'],
                        'b-carousel-slide': 'img-src',
                        'b-embed': 'src'
                    }
                }
            }
        ]
    }
}

