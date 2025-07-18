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
    Route::group(['prefix' => 'documentmanager',  'middleware' => 'auth'], function() {
    Route::get('/', 'DocumentManagerController@index')->name('documentmanager');
	//Route::get('/print', 'DocumentManagerController@ajaxListAllDocument')->name('documentmanager.print');
	Route::get('/view/{rowcount}', 'DocumentManagerController@show')->name('documentmanager.view');
	Route::get('/all/ajax/lists', 'DocumentManagerController@ajaxListAllDocument')->name('documentmanager.ajax.list.all');
});



