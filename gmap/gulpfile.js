var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

//elixir(function(mix) {
//    mix.sass('app.scss');
//});

elixir(function(mix) {
    //mix.less('app.less');
    mix.scripts([
        "jquery/jquery-2.1.4.min.js",
        "bootstrap/js/bootstrap.js",
        //"slider/js/modernizr.custom.js",
        //"slider/js/classie.js"
    ],null,'resources/assets/');

    mix.styles([
        "bootstrap/css/bootstrap.min.css",
        //"slider/css/component.css",
        //"slider/css/default.css",
    ],null,'resources/assets/');

    mix.version(['css/all.css','js/all.js']);
});