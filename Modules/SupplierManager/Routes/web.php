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

Route::group(['prefix'=> 'suppliers', 'middleware' => 'auth', 'as'=> 'suppliers.'], function() {
    Route::get('/', 'SupplierManagerController@index')->name('index');
    Route::get('/add', 'SupplierManagerController@create')->name('create');
    Route::post('/add', 'SupplierManagerController@store')->name('store');
    Route::get('/view/{id}', 'SupplierManagerController@show')->name('view');
    Route::get('/edit/{id}', 'SupplierManagerController@edit')->name('edit');
    Route::patch('/{id}/update', 'SupplierManagerController@update')->name('update');
    Route::get('/{id}/delete', 'SupplierManagerController@destroy')->name('delete');
    Route::get('/profile', 'SupplierManagerController@profile')->name('profile');
    Route::patch('/{id}/profile', 'SupplierManagerController@updateProfile')->name('update.profile');
    Route::get('/import-suppliers', 'SupplierManagerController@import')->name('import');
    Route::post('/import-suppliers', 'SupplierManagerController@doImport')->name('do.import');
    Route::get('/all-suppliers', 'SupplierManagerController@ajaxListAllSuppliers')->name('ajax.list.all');
});
