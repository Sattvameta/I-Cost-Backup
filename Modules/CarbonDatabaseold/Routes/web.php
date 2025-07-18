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
Route::group(['prefix' => 'carbondatabase',  'middleware' => 'auth'], function() {
    Route::get('/', 'CarbonDatabaseController@index')->name('carbondatabase');
	
	Route::get('/all/ajax/lists', 'CarbonDatabaseController@ajaxListAllCarbon')->name('carbondatabase.ajax.list.all');
});

