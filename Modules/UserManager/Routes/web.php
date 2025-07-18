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

Route::group(['prefix'=> 'users', 'middleware' => 'auth', 'as'=> 'users.'], function() {
    Route::get('/', 'UserManagerController@index')->name('index');
    Route::get('/admins', 'UserManagerController@admins')->name('admins');
    Route::get('/add', 'UserManagerController@create')->name('create');
    Route::post('/add', 'UserManagerController@store')->name('store');
    Route::get('/view/{id}', 'UserManagerController@show')->name('view');
    Route::get('/adminview/{id}', 'UserManagerController@showadmin')->name('adminview');
    Route::get('/adminedit/{id}', 'UserManagerController@adminedit')->name('adminedit');
    Route::get('/edit/{id}', 'UserManagerController@edit')->name('edit');
    Route::patch('/{id}/update', 'UserManagerController@update')->name('update');

    Route::get('/{id}/delete', 'UserManagerController@destroy')->name('delete');
    Route::get('/profile', 'UserManagerController@profile')->name('profile');
    Route::patch('/profile', 'UserManagerController@updateProfile')->name('update.profile');
    Route::get('/password', 'UserManagerController@password')->name('password');
    Route::post('/password', 'UserManagerController@changePassword')->name('change.password');
    Route::get('/all-users', 'UserManagerController@ajaxListAllUsers')->name('ajax.list.all');
    Route::get('/all-admins', 'UserManagerController@ajaxListAllAdmins')->name('ajax.list.admins');
	Route::get('/project/{id}', 'UserManagerController@project')->name('project');
	Route::post('/', 'UserManagerController@storeproject')->name('storeproject');
	Route::get('/editproject/{id}', 'UserManagerController@editproject')->name('editproject');
	Route::get('/updateproject/{id}', 'UserManagerController@updateproject')->name('updateproject');
});
