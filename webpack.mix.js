let mix = require('laravel-mix');

mix.js('resources/assets/js/common.js', 'public/.assets/app')
    .sourceMaps()
    //.extract(['axios','vue'])
//mix.sass('resources/assets/sass/app.scss', 'public/.assets/css');
