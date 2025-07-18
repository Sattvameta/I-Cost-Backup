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

Route::group(['prefix' => 'timesheets',  'middleware' => 'auth'], function() {
    Route::get('/staff/{id?}', 'TimesheetManagerController@staffTimesheets')->name('timesheets.staff');
    Route::get('/labour/{id?}', 'TimesheetManagerController@labourTimesheets')->name('timesheets.labour');
    Route::get('/weekly-staff/{id?}', 'TimesheetManagerController@staffTimesheetsWeekly')->name('timesheets.staff.weekly');
    Route::get('/weekly-labour/{id?}', 'TimesheetManagerController@labourTimesheetsWeekly')->name('timesheets.labour.weekly');

    ## Staff timesheet ##
    Route::get('/staff/{id?}/create', 'TimesheetManagerController@createStaffTimesheet')->name('timesheets.staff.create');
    Route::post('/staff/{id?}/store', 'TimesheetManagerController@storeStaffTimesheet')->name('timesheets.staff.store');
    Route::get('/staff/{id?}/edit', 'TimesheetManagerController@editStaffTimesheet')->name('timesheets.staff.edit');
    Route::get('/staff/{id?}/edit/print', 'TimesheetManagerController@editprintStaffTimesheet')->name('timesheets.staff.edit.print');
    Route::patch('/staff/{id?}/update', 'TimesheetManagerController@updateStaffTimesheet')->name('timesheets.staff.update');
    Route::get('/staff/{id?}/delete', 'TimesheetManagerController@deleteStaffTimesheet')->name('timesheets.staff.delete');
    Route::get('/staff/{id?}/print', 'TimesheetManagerController@printStaffTimesheet')->name('timesheets.staff.print');
    Route::get('/staff/{id?}/gallery', 'TimesheetManagerController@galleryStaffTimesheet')->name('timesheets.gallery.staff');
    Route::get('/staff/{id?}/download', 'TimesheetManagerController@downloadStaffTimesheetFile')->name('timesheets.download.staff.file');

    ## Labour timesheet ##
    Route::get('/labour/{id?}/create', 'TimesheetManagerController@createLabourTimesheet')->name('timesheets.labour.create');
    Route::get('/labour/{id?}/createseperate', 'TimesheetManagerController@createseperateLabourTimesheet')->name('timesheets.labour.createseperate');
    Route::post('/labour/{id?}/store', 'TimesheetManagerController@storeLabourTimesheet')->name('timesheets.labour.store');
    Route::post('/labour/{id?}/storeseperate', 'TimesheetManagerController@storeseperateLabourTimesheet')->name('timesheets.labour.storeseperate');
    Route::get('/labour/{id?}/edit', 'TimesheetManagerController@editLabourTimesheet')->name('timesheets.labour.edit');
    Route::patch('/labour/{id?}/update', 'TimesheetManagerController@updateLabourTimesheet')->name('timesheets.labour.update');
    Route::get('/labour/{id?}/delete', 'TimesheetManagerController@deleteLabourTimesheet')->name('timesheets.labour.delete');
    Route::get('/labour/{id?}/print', 'TimesheetManagerController@printLabourTimesheet')->name('timesheets.labour.print');
    Route::get('/labour/{id?}/gallery', 'TimesheetManagerController@galleryLabourTimesheet')->name('timesheets.gallery.labour');
    Route::get('/labour/{id?}/download', 'TimesheetManagerController@downloadLabourTimesheetFile')->name('timesheets.download.labour.file');
   

    
    ## Ajax timesheet
    Route::group(['prefix'=> 'ajax'], function(){

        ## Labour timesheet ##
        Route::get('/labour', 'TimesheetManagerAjaxController@labourTimesheets')->name('timesheets.ajax.labour');
        Route::get('/timesheet-labour-form', 'TimesheetManagerAjaxController@getTimesheetLabourForm')->name('timesheets.ajax.labour.timesheet.form');
        
        Route::get('/weekly-labour-timesheet', 'TimesheetManagerAjaxController@labourWeeklyTimesheets')->name('timesheets.ajax.labour.weekly');
        Route::get('/approve-laour-timesheet', 'TimesheetManagerAjaxController@approveLabourTimesheet')->name('timesheets.ajax.labour.approve');
        
        ## Staff timesheet ##
        Route::get('/staff', 'TimesheetManagerAjaxController@staffTimesheets')->name('timesheets.ajax.staff');
        Route::get('/timesheet-staff-form', 'TimesheetManagerAjaxController@getTimesheetStaffForm')->name('timesheets.ajax.staff.timesheet.form');
    
        Route::get('/weekly-staff-timesheet', 'TimesheetManagerAjaxController@staffWeeklyTimesheets')->name('timesheets.ajax.staff.weekly');
        Route::get('/approve-staff-timesheet', 'TimesheetManagerAjaxController@approveStaffTimesheet')->name('timesheets.ajax.staff.approve');
        
    });
});
