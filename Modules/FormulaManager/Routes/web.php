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

Route::group(['prefix' => 'formulas',  'middleware' => 'auth'], function() {
    Route::get('/', 'FormulaManagerController@index')->name('formulas');
    Route::get('/add', 'FormulaManagerController@create')->name('formulas.add');
    Route::post('/add', 'FormulaManagerController@store')->name('formulas.store');
    Route::get('/view/{id}', 'FormulaManagerController@show')->name('formulas.view');
    Route::get('/edit/{id}', 'FormulaManagerController@edit')->name('formulas.edit');
    Route::patch('/{id}/edit', 'FormulaManagerController@update')->name('formulas.update');
    Route::get('/delete/{id}', 'FormulaManagerController@destroy')->name('formulas.delete');
    Route::get('/all/ajax/lists', 'FormulaManagerController@ajaxListAllFormulas')->name('formulas.ajax.list.all');
});
