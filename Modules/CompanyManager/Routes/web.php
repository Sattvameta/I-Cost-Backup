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

Route::group(['prefix'=> 'companies', 'middleware' => 'auth', 'as'=> 'companies.'], function() {
    Route::get('/', 'CompanyManagerController@index')->name('index');
    Route::get('/add', 'CompanyManagerController@create')->name('create');
    Route::post('/add', 'CompanyManagerController@store')->name('store');
    Route::get('/view/{id}', 'CompanyManagerController@show')->name('view');
    Route::get('/edit/{id}', 'CompanyManagerController@edit')->name('edit');
    Route::patch('/{id}/update', 'CompanyManagerController@update')->name('update');
    Route::get('/{id}/delete', 'CompanyManagerController@destroy')->name('delete');
    Route::get('/profile', 'CompanyManagerController@profile')->name('profile');
    Route::patch('/{id}/profile', 'CompanyManagerController@updateProfile')->name('update.profile');
    Route::get('/all-companies', 'CompanyManagerController@ajaxListAllCompanies')->name('ajax.list.all');
	Route::get('/userlist/{id}', 'CompanyManagerController@userlist')->name('userlist');


    
    
    
    ## Features ##
    Route::get('/{id}/features', 'FeaturesController@index')->name('features');
    Route::post('/update-features', 'FeaturesController@updateFeatures')->name('features.update');
    ## Permission ##
    Route::get('/{id}/permission', 'PermissionsController@companyPermissions')->name('permissions');
    
    
    
});

Route::group(['prefix'=> 'permissions', 'middleware' => 'auth', 'as'=> 'permissions.'], function() {
    Route::get('/', 'PermissionsController@index')->name('index');
    Route::get('/permission/{id?}/', 'PermissionsController@getPermissions')->name('roles');
    Route::get('/permissions-edit/{id}', 'PermissionsController@edit')->name('edit');
    Route::get('/company-permissions/{id}', 'PermissionsController@company')->name('company');
    Route::get('/get-company-permissions/{id?}', 'PermissionsController@getCompanyPermissions')->name('companyroles');
    Route::post('/update', 'PermissionsController@updatePermissions')->name('update');
});