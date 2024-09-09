let mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/home.js', 'public/js')
    .js('resources/js/login.js', 'public/js')
    .js('resources/bootstrap/js/bootstrap.min.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css')
    .postCss('resources/css/home.css', 'public/css')
    .postCss('resources/css/login.css', 'public/css')
    .postCss('resources/css/dashboard.css', 'public/css')
    .postCss('resources/bootstrap/css/bootstrap.min.css', 'public/css')
    .setPublicPath('public');