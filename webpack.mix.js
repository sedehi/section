let mix = require('laravel-mix');

mix.webpackConfig(webpack => {
    return {
        plugins: [
            new webpack.ProvidePlugin({
                $: 'jquery',
                jQuery: 'jquery',
                'window.jQuery': 'jquery',
                Popper: ['popper.js', 'default'],
            })
        ]
    };
});

mix.setPublicPath('assets');
mix.options({
    processCssUrls: false,
});

mix.js('resources/assets/js/app.js', 'js')
.sass('resources/assets/sass/app.scss', 'css')
.copy('resources/assets/js/datepicker/persian-date.min.js', 'assets/js')
.copyDirectory('resources/assets/static/fonts', 'assets/static/fonts')
.copyDirectory('resources/assets/static/images', 'assets/images');

