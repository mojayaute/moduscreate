<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/vehicles/{model_year}/{manufacturer}/{model}','vehiclesController@getCars'); 
Route::post('/vehicles','vehiclesController@postCar'); 
Route::get('/vehicles/requeriment2','vehiclesController@requeriment2')->name('requeriment2'); 