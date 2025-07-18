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

Route::group(['prefix'=> 'cost', 'middleware' => 'auth', 'as'=> 'cost.'], function() {
    Route::get('/', 'CostManagerController@index')->name('index');
    
    Route::get('/project/list', 'CostManagerController@listall')->name('project.list.all');
   
});

