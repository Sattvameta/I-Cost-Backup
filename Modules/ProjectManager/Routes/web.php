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

Route::group(['prefix' => 'projects',  'middleware' => 'auth'], function() {
    Route::get('/', 'ProjectsController@index')->name('projects');
    Route::get('/add', 'ProjectsController@create')->name('projects.add');
    Route::post('/add', 'ProjectsController@store')->name('projects.store');
    Route::get('/view/{id}', 'ProjectsController@show')->name('projects.view');
    Route::get('/edit/{id}', 'ProjectsController@edit')->name('projects.edit');
    Route::get('/delete/{id}', 'ProjectsController@destroy')->name('projects.delete');
    Route::patch('/{id}/edit', 'ProjectsController@update')->name('projects.update');
    Route::get('/new-version', 'ProjectsController@createProjectNewVersion')->name('projects.new.version');
    Route::post('/new-version', 'ProjectsController@storeProjectNewVersion')->name('projects.store.new.version');
    Route::get('/assign-users/{id}', 'ProjectsController@assignProjectUsers')->name('projects.assign.users');
    Route::post('/assign-users', 'ProjectsController@storeProjectUsers')->name('projects.store.assign.users');
    Route::get('/all/ajax/lists', 'ProjectsController@ajaxListAllProjects')->name('project.ajax.list.all');

    Route::patch('/make/default', 'ProjectsController@makeDefault')->name('projects.make.default');
});
