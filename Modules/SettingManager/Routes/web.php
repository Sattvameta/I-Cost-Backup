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
Route::group(['prefix' => 'settings',  'middleware' => 'auth'], function() {
    Route::get('logos', 'SettingManagerController@getlogos')->name('settingtheme');
    Route::delete('themedelete/{id}', 'SettingManagerController@themedelete')->name('setting.deletelogo');
    Route::post('update-theme-images','SettingManagerController@storeLogos')->name('setting.logo.update');
    Route::post('store-image-temp','SettingManagerController@storeTempImage');
    Route::get('smtp','SettingManagerController@getSmtpSetting')->name('setting.smtp');
    Route::post('save-smtp-settings','SettingManagerController@updateSmtpSetting')->name('setting.smtp.update');
    Route::get('general','SettingManagerController@getGeneralSetting')->name('setting.general');
    Route::get('general/add','SettingManagerController@addGeneralSetting')->name('setting.general.add');
    Route::post('general/add','SettingManagerController@storeGeneralSetting')->name('setting.general.store');
    Route::get('general/view/{id}','SettingManagerController@showGeneralSetting')->name('setting.general.view');
    Route::get('general/edit/{id}','SettingManagerController@editGeneralSetting')->name('setting.general.edit');
    Route::patch('general/{id}/edit','SettingManagerController@updateGeneralSetting')->name('setting.general.update');
    
    Route::get('helpdesk','SettingManagerController@helpdesk')->name('helpdesk');
    Route::get('helpdesk/{id}','SettingManagerController@helpdeskquery')->name('helpdesk.id');
    Route::get('/all-querries', 'SettingManagerController@ajaxListAllQuery')->name('ajax.list.query');
    Route::post('queryreview', 'SettingManagerController@queryreview')->name('queryreview');
    Route::get('querycreate', 'SettingManagerController@querycreate')->name('querycreate');
    Route::post('savequery', 'SettingManagerController@savequery')->name('savequery');
});


Route::group(['prefix' => 'helpdesk',  'middleware' => 'auth'], function() {
    
});