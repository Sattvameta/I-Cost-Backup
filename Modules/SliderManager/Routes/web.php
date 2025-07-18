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

Route::group(['prefix' => 'sliders',  'middleware' => 'auth'], function() {
    Route::get('/', 'SlidersController@index')->name('sliders');
     Route::get('/systemtour', 'SlidersController@systemtour')->name('sliders.systemtour');
        Route::get('/add', 'SlidersController@create')->name('sliders.add');
        Route::post('/add', 'SlidersController@store')->name('sliders.store');
        Route::get('/view/{id}', 'SlidersController@show')->name('sliders.view');
        Route::get('/edit/{id}', 'SlidersController@edit')->name('sliders.edit');
         Route::get('/delete/{id}', 'SlidersController@destroy')->name('sliders.delete');
        Route::patch('/{id}/edit', 'SlidersController@update')->name('sliders.update');
  Route::get('/role/all/ajax/lists', 'SlidersController@ajaxListAllSliders')->name('slider.ajax.list.all');
});
