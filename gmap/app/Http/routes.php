<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('map', function () {
    return view('map');
});
Route::get('modal', function () {
    return view('modal');
});

Route::get('map2', function () {
    return view('map2');
});

Route::get('map3', function () {
    return view('map3');
});

Route::get('slider', function () {
    return view('collapse');
});

Route::get('distributors', function () {
    return view('distributors');
});

Route::post('geo_location', 'GeoLocationController@create');

get('places', 'GeoLocationController@getPlaces');
