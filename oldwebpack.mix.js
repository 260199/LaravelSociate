const mix = require('laravel-mix');

// Mengompilasi file JavaScript dan CSS
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');
