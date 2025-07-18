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

Route::prefix('carboncalculator')->group(function() {
 
	 Route::get('/', 'CarbonCalculatorController@index')->name('carboncalculator');
	 Route::get('/add', 'CarbonCalculatorController@create')->name('add');
	 Route::get('/{id}/delete', 'CarbonCalculatorController@destroy')->name('delete');
	 Route::get('/all/ajax/lists', 'CarbonCalculatorController@ajaxListAllCarbon')->name('carboncalculator.ajax.list.all');
	 Route::post('/store', 'CarbonCalculatorController@store')->name('store');
	 Route::get('/{id}/view', 'CarbonCalculatorController@show')->name('view');
	 Route::get('/carbonimport', 'CarbonCalculatorController@importProjectCarbonView')->name('carbon.projects.imp');
	 Route::post('/carbonupload', 'CarbonCalculatorController@importProjCarbon')->name('carbonupload');
});
