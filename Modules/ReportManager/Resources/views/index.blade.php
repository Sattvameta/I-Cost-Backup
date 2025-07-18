@extends('user::layouts.masterlist')

@section('content')
<style type="text/css">
    .select2.select2-container{
        width: 100% !important;
    }
    .vfont{
        writing-mode: vertical-lr; 
        transform: rotate(180deg);                
        -webkit-transform: rotate(180deg);   
        -moz-transform: rotate(180deg);
        -ms-transform: rotate(180deg);
        -o-transform: rotate(180deg);
        transform: rotate(180deg);                      
        color: #ffffff;						  
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Reports</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">

          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item active">Reports</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::label('report_for_filter', 'Report For') }}
                        {{ Form::select('project', [
                            ''=> '---', 
                            'project'=> 'Project', 
                            'purchase-order'=> 'Purchase Order', 
                            'staff-timesheet'=> 'Staff Timesheet',
                            'staff-weekly-timesheet'=> 'Staff Weekly Timesheet', 
                            'staff-timesheet-info'=> 'Staff Timesheet Detail', 
                            'labour-timesheet-info'=> 'Labour Timesheet Detail',
							'carbon-calculator'=> 'Carbon Calculator',
                            ], null, [
                            'class' => "form-control multiselect-dropdown report_for_filter",
                            'id' => "report_for_filter",
                            'data-live-search'=>'true',
                            'onchange'=>'getReports(null, this.value)'
                        ]) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::label('project_filter', 'Project') }}
                        {{ Form::select('project', $allProjects, null, [
                            'class' => "form-control multiselect-dropdown project_filter",
                            'id' => "project_filter",
                            'data-live-search'=>'true',
                            'onchange'=>'getReports(this.value, null)'
                        ]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Default box -->
    @include('layouts.flash.alert')
    <div class="card reports-wrapper">
        
    </div>
</section>
@stop
@push('scripts')
<script type="text/javascript">
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    
    $(document).ready(function(){
        $(document).on('click', '#expand_all', function() {
            $(document).find('.expandable .expandable-input').val("-");
            $(document).find('.expandable').nextAll('tr').each(function() {
                if (!($(this).is('.expandable')))
                $(this).show();
            });
        });
        $(document).on('click', '#collaps_all', function() {
            $(document).find('.expandable .expandable-input').val("+");
            $(document).find('.expandable').nextAll('tr').each(function() {
                if (!($(this).is('.expandable')))
                    $(this).hide();
            });
        });

        $(document).on('click', '.expandable-input', function() { 
            if($(this).closest('td .expandable-input').val() == "+"){
                $(this).closest('td .expandable-input').val("-");
            }else if($(this).closest('td .expandable-input').val() == "-"){
                $(this).closest('td .expandable-input').val("+");
            }
            var trElem = $(this).closest("tr");
            trElem.nextAll('tr').each(function() {
                if ($(this).is('.expandable')) {
                    return false;
                }
                $(this).toggle();
            });
        });

        $(document).on('click', '.filter-staff-timesheet', function(){
            filterStaffTimesheet();
        });
        $(document).on('click', '.filter-staff-weekly-timesheet', function(){
            filterStaffWeeklyTimesheet();
        });

        getReports();
    });

    function getReports(projectId = null, reportFor = null){
        if(!projectId){
            projectId = $(document).find('.project_filter').val();
        }
        if(!reportFor){
            reportFor = $(document).find('.report_for_filter').val();
        }

        if(reportFor == 'project'){
            getProjectReport(projectId);
        }else if(reportFor == 'purchase-order'){
            getPurchaseReport(projectId);
        }else if(reportFor == 'staff-timesheet'){
            getStaffTimesheetReport(projectId);
        }else if(reportFor == 'staff-timesheet-info'){
            getStaffTimesheetReportInfo(projectId);
        }else if(reportFor == 'staff-weekly-timesheet'){
            getWeeklyStaffTimesheetReport(projectId);
        }else if(reportFor == 'labour-timesheet-info'){
            getLabourTimesheetReportInfo(projectId);
        }else if(reportFor == 'carbon-calculator'){
            getCarbonCalculatorReport(projectId);
        }else{
            $(document).find('.reports-wrapper').html('<div class="card-body"><div class="alert alert-warning">Please select report type.</div></div>');
            return;
        }
        
    }
    /* Get project report */
    function getProjectReport(projectId = null){
        var route = "{{ route('reports.ajax.project.report.list') }}";
        var route = route+"?project_id="+projectId;
        $.get(route, function(data){
            if(data.html == ''){
                data.html = '<div class="card-body"><div class="alert alert-warning">Sorry! no record found.</div></div>';
            }
            $(document).find('.reports-wrapper').html(data.html).promise().done(function(){
                $(document).find('.expandable').next('tr').each(function() {
                    if (!($(this).is('.expandable'))){
                        $(this).hide();
                    }
                });
            });
        });
    }
    /* Get purchase report */
    function getPurchaseReport(projectId){
        if(!projectId){
            $(document).find('.reports-wrapper').html('<div class="card-body"><div class="alert alert-warning">Please select project to view staff timesheet report.</div></div>');
            return;
        }
        var route = "{{ route('reports.ajax.purchase.report.list') }}";
        var route = route+"?project_id="+projectId;
        $.get(route, function(data){
            if(data.html == ''){
                data.html = '<div class="card-body"><div class="alert alert-warning">Sorry! no record found.</div></div>';
            }
            $(document).find('.reports-wrapper').html(data.html).promise().done(function(){});
        });
    }

    /* Get staff timesheet report */
    function getStaffTimesheetReport(projectId){
        if(!projectId){
            $(document).find('.reports-wrapper').html('<div class="card-body"><div class="alert alert-warning">Please select project to view staff timesheet report.</div></div>');
            return;
        }
        var route = "{{ route('reports.ajax.staff.timesheet.report.list') }}";
        var route = route+"?project_id="+projectId;
        $.get(route, function(data){
            if(data.html == ''){
                data.html = '<div class="card-body"><div class="alert alert-warning">Sorry! no record found.</div></div>';
            }
            $(document).find('.reports-wrapper').html(data.html).promise().done(function(){
                filterStaffTimesheet();
            });
        });
    }

    /* Get weekly staff timesheet report */
    function getWeeklyStaffTimesheetReport(projectId){
        if(!projectId){
            $(document).find('.reports-wrapper').html('<div class="card-body"><div class="alert alert-warning">Please select project to view weekly staff timesheet report.</div></div>');
            return;
        }
        var route = "{{ route('reports.ajax.weekly.staff.timesheet.report.list') }}";
        var route = route+"?project_id="+projectId;
        $.get(route, function(data){
            if(data.html == ''){
                data.html = '<div class="card-body"><div class="alert alert-warning">Sorry! no record found.</div></div>';
            }
            $(document).find('.reports-wrapper').html(data.html).promise().done(function(){
                filterStaffWeeklyTimesheet();
            });
        });
    }

    /* Get staff timesheet report info */
    function getStaffTimesheetReportInfo(projectId){
		//alert(projectId);
        var route = "{{ route('reports.ajax.staff.timesheet.report.info') }}";
        var route = route+"?project_id="+projectId;
		//alert(projectId);
        $.get(route, function(data){
            if(data.html == ''){
                data.html = '<div class="card-body"><div class="alert alert-warning">Sorry! no record found.</div></div>';
            }
            $(document).find('.reports-wrapper').html(data.html).promise().done(function(){
                $(document).find('.expandable').next('tr').each(function() {
                    if (!($(this).is('.expandable'))){
                        $(this).hide();
                    }
                });
            });
        });
    }


    /* Get labour timesheet report info */
    function getLabourTimesheetReportInfo(projectId){
        if(!projectId){
            $(document).find('.reports-wrapper').html('<div class="card-body"><div class="alert alert-warning">Please select project to view labour timesheet report.</div></div>');
            return;
        }
        var route = "{{ route('reports.ajax.labour.timesheet.report.info') }}";
        var route = route+"?project_id="+projectId;
        $.get(route, function(data){
            if(data.html == ''){
                data.html = '<div class="card-body"><div class="alert alert-warning">Sorry! no record found.</div></div>';
            }
            $(document).find('.reports-wrapper').html(data.html).promise().done(function(){
                $(document).find('.expandable').next('tr').each(function() {
                    if (!($(this).is('.expandable'))){
                        $(this).hide();
                    }
                });
            });
        });
    }
    
	 /* Get carbon calculator report info */
    function getCarbonCalculatorReport(projectId){
        if(!projectId){
            $(document).find('.reports-wrapper').html('<div class="card-body"><div class="alert alert-warning">Please select project to view Carbon calculator report.</div></div>');
            return;
        }
        var route = "{{ route('reports.ajax.carbon.calculator.report.info') }}";
        var route = route+"?project_id="+projectId;
        $.get(route, function(data){
            if(data.html == ''){
                data.html = '<div class="card-body"><div class="alert alert-warning">Sorry! no record found.</div></div>';
            }
            $(document).find('.reports-wrapper').html(data.html).promise().done(function(){
                $(document).find('.expandable').next('tr').each(function() {
                    if (!($(this).is('.expandable'))){
                        $(this).hide();
                    }
                });
            });
        });
    }
	
    function filterStaffTimesheet(){
        var projectId = $(document).find('.project_filter').val();
        var fromDate = $(document).find('.date_from_filter').val();
        var toDate = $(document).find('.date_to_filter').val();
        var userId = $(document).find('.user_filter').val();
        var role = $(document).find('.role_filter').val();
        var route = "{{ route('reports.ajax.staff.timesheet.report.filter') }}";
        var route = route+"?project_id="+projectId+'&form_date='+fromDate+'&to_date='+toDate+'&user_id='+userId+'&role='+role;
        $.get(route, function(data){
            if(data.html == ''){
                data.html = '<div class="card-body"><div class="alert alert-warning">Sorry! no record found.</div></div>';
            }
            $(document).find('.timesheets-list-wrapper').html(data.html).promise().done(function(){});
        });
    }

    function filterStaffWeeklyTimesheet(){
        var projectId = $(document).find('.project_filter').val();
        var fromDate = $(document).find('.date_from_filter').val();
        var userId = $(document).find('.user_filter').val();
        var role = $(document).find('.role_filter').val();
        var route = "{{ route('reports.ajax.weekly.staff.timesheet.report.filter') }}";
        var route = route+"?project_id="+projectId+'&form_date='+fromDate+'&user_id='+userId;

        if(!fromDate){
            $(document).find('.timesheets-list-wrapper').html('<div class="card-body"><div class="alert alert-warning">Please select date to filter timesheet.</div></div>');
            return;
        }
        $.get(route, function(data){
            if(data.html == ''){
                data.html = '<div class="card-body"><div class="alert alert-warning">Sorry! no record found.</div></div>';
            }
            $(document).find('.timesheets-list-wrapper').html(data.html).promise().done(function(){});
        });
    }

    function printDiv(divName) {
        if(!document.getElementById(divName)){
            return;
        }
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

</script>
@endpush