@extends('user::layouts.master')

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
    
      <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">

          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item active">Timesheets</li>
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
                        {{ Form::label('project_filter', 'Project') }}
                        {{ Form::select('project', $allProjects, $user->default_project, [
                            'class' => "form-control multiselect-dropdown project_filter",
                            'id' => "project_filter",
                            'data-live-search'=>'true',
                        ]) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::label('user_filter', 'User') }}
                        {{ Form::select('user', $allUsers, null, [
                            'class' => "form-control multiselect-dropdown user_filter",
                            'id' => "user_filter",
                            'data-live-search'=>'true',
                        ]) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::label('date_filter', 'From') }}
                        {{ Form::date('date',  date('Y-m-d'), [
                            'class' => "form-control date_filter",
                            'id' => "date_filter",
                        ]) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <br>
                        <a class="btn btn-primary btn-lg find-weekly-timesheet" href="javascript:;">Find</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Default box -->
    <div class="card timesheet-card" style="display:none">
        <div class="card-header">
            <h3 class="card-title">Timesheets</h3><hr>
            <div class="card-tools">
                <a class="btn btn-success btn-sm" href="javascript:;" onclick="printDiv('printable')">Print</a>
            </div>
        </div>
        <div class="card-body timesheet-wrapper printable" id="printable">
        </div>
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

        var project = $(document).find('.project_filter').val();
        var user    = $(document).find('.user_filter').val();
        var date   = $(document).find('.date_filter').val();

        $(document).on('click', '.find-weekly-timesheet', function(){
            project = $(document).find('.project_filter').val();
            user    = $(document).find('.user_filter').val();
            date   = $(document).find('.date_filter').val();
            if(project && user && date){
                getTimesheets(project, user, date);
            }
        });

        if(project && user && date){
            getTimesheets(project, user, date);
        }
        
    });

    function getTimesheets(project, user, date){
        var route = "{{ route('timesheets.ajax.staff.weekly') }}";
        route +="?project="+project+"&user="+user+"&date="+date;
        $.get(route, function(data){
            $(document).find('.timesheet-wrapper').html(data.html).promise().done(function(){
                $(document).find('.timesheet-card').show();
                console.log('success');
            });
        });
    }

    function approveTimesheets(timesheetId, projectId, activityId, monday, sunday, supervisor){
        var isChecked = document.getElementById('timesheet_approval'+timesheetId).checked;
        if(isChecked){
			var route = "{{ route('timesheets.ajax.staff.approve') }}";
            route +="?project_id="+projectId+"&activity_id="+activityId+"&monday="+monday+"&sunday="+sunday+"&supervisor="+supervisor;
            $.get(route, function(data){
                alert(data.message);
            });
		}
    }

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }

</script>
@endpush