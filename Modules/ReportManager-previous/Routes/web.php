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

Route::group(['prefix'=> 'reports', 'middleware' => 'auth', 'as'=> 'reports.'], function() {
    Route::get('/', 'ReportManagerController@index')->name('index');
    Route::get('/purchase-report/{id}', 'ReportManagerController@viewPurchaseReport')->name('view.purchase.report');
    Route::get('/export-staff-timesheet-report/{id}', 'ReportManagerController@exportStaffTimesheet')->name('export.staff.timesheet.report');
    Route::get('/export-labour-timesheet-report/{id}', 'ReportManagerController@exportLabourTimesheet')->name('export.labour.timesheet.report');
	Route::get('/export-purchase-report/{id}', 'ReportManagerController@exportPurchase')->name('export.purchase.report');
    ## Ajax timesheet
    Route::group(['prefix'=> 'ajax', 'as'=> 'ajax.'], function(){
        ## Project report  ##
        Route::get('/project-report', 'ReportManagerAjaxController@projectReports')->name('project.report.list');
        ## Purchase report  ##
        Route::get('/purchase-report', 'ReportManagerAjaxController@purchaseReports')->name('purchase.report.list');
        ## Staff timesheet report  ##
        Route::get('/staff-timesheet-report', 'ReportManagerAjaxController@staffTimesheetReports')->name('staff.timesheet.report.list');
        Route::get('/staff-timesheet-report-info', 'ReportManagerAjaxController@staffTimesheetReportInfo')->name('staff.timesheet.report.info');
        Route::get('/staff-timesheet-report-filter', 'ReportManagerAjaxController@staffTimesheetReportFilter')->name('staff.timesheet.report.filter');
        Route::get('/weekly-staff-timesheet-report', 'ReportManagerAjaxController@weeklyStaffTimesheetReports')->name('weekly.staff.timesheet.report.list');
        Route::get('/weekly-staff-timesheet-report-filter', 'ReportManagerAjaxController@weeklyStaffTimesheetReportFilter')->name('weekly.staff.timesheet.report.filter');

        ## Labour timesheet report  ##
        Route::get('/labour-timesheet-report-info', 'ReportManagerAjaxController@labourTimesheetReportInfo')->name('labour.timesheet.report.info');
    });
});
