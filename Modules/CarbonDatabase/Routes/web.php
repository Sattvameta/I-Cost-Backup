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
	 Route::post('/store', 'CarbonDatabaseController@store')->name('store');
	Route::get('/all/ajax/lists', 'CarbonDatabaseController@ajaxListAllCarbon')->name('carbondatabase.ajax.list.all');
	Route::get('/all/ajax/list', 'CarbonDatabaseController@ajaxListAllCarbonmaterial')->name('carbon.ajax.list.all');
	Route::get('/all/ajax/listed', 'CarbonDatabaseController@ajaxListCarbon')->name('carbon.ajax.listed.all');
	Route::get('/all/ajax/ghg', 'CarbonDatabaseController@ajaxGhgListCarbon')->name('carbon.ajax.ghg.all');
	Route::get('/carbonprojimport', 'CarbonDatabaseController@importProjCarbonView')->name('carbon.proj.import');
	 Route::post('/carbonprojupload', 'CarbonDatabaseController@importCarbonUpload')->name('carbonprojupload');
	
});

