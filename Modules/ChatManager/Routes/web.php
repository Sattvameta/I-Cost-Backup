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

Route::group(['prefix'=> 'conversations', 'middleware' => 'auth', 'as'=> 'conversations.'], function() {
    Route::get('/{id?}', 'ChatManagerController@index')->name('index');
    Route::post('/send-message', 'ChatManagerController@sendMessage')->name('send');
    Route::get('/all/notifications', 'ChatManagerController@getNotifications')->name('notifications');
    Route::get('/search/contacts', 'ChatManagerController@getContacts')->name('search.contacts');
});
