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

Route::group(['prefix'=> 'gantt', 'middleware' => 'auth', 'as'=> 'gantt.'], function() {
    Route::get('/', 'GanttManagerController@index')->name('index');
    Route::get('api/data/{id}', 'GanttManagerController@get');
});
 Route::post('link','LinkController@store');
 Route::delete('link/{id}','LinkController@destroy');
 Route::delete('task/{id}','TaskController@destroy');
 Route::post('task/{id}','TaskController@update');
 
 Route::get('/ganttimport', 'GanttManagerController@importProjectGanttView')->name('gantt.projects.import.view');
 Route::post('/ganttactivities', 'GanttManagerController@importProjectGantt')->name('gantt.projects.import');
 //Route::get('/{id?}', 'GanttManagerController@projectGantt')->name('gantt.projects');