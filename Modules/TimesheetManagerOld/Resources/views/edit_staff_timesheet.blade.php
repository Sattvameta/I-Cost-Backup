@extends('user::layouts.master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
 
      <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('timesheets.staff', $timesheet->project_id) }}">Timesheets</a></li>
          <li class="breadcrumb-item active">Edit Timesheet</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    @include('layouts.flash.alert')
    <div class="card">
        {{ Form::model($timesheet, ['route' => ['timesheets.staff.update', $timesheet->id], 'method' => 'patch', 'enctype'=> 'multipart/form-data']) }}
        <input type="hidden" name="id" value="{{ $timesheet->id }}">
        <div class="card-header">
            <h3 class="card-title">Edit Timesheet</h3><hr>
            <div class="card-tools">
                <a class="btn btn-warning btn-sm" href="{{ route('timesheets.staff', $timesheet->project_id) }}" title="Back">Back</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table-responsive table table-bordered">
                <thead>
                    <tr class="table-success">
                        <th>Activity Code</th>
                        <th>Activity</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Hours</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="tr{{ $timesheet->id }}">
                            {{ $timesheet->activityOfTimesheet->item_code }}
                        </td>
                        <td class="tr{{ $timesheet->id }}">
                            {{ $timesheet->activity }}
                        </td>
                        <td class="tr{{ $timesheet->id }}">
                            <input type="date" value="{{ $timesheet->timesheet_date }}" name="timesheet_date" data-id="{{ $timesheet->id }}" id="timesheet_date{{ $timesheet->id }}"  class="form-control timesheet_date">
                        </td>
                        <td class="tr{{ $timesheet->id }}">
                            <input step="any" type="time" value="{{ $timesheet->start_time }}" name="start_time" data-id="{{ $timesheet->id }}" id="start_time{{ $timesheet->id }}"  class="form-control start_time">
                        </td>
                        <td class="tr{{ $timesheet->id }}">
                            <input step="any" type="time" value="{{ $timesheet->end_time }}" name="end_time" data-id="{{ $timesheet->id }}" id="end_time{{ $timesheet->id }}"  class="form-control end_time">
                        </td>
                        <td class="tr{{ $timesheet->id }}">
                            <input type="text" value="{{ $timesheet->hours }}" name="hours" data-id="{{ $timesheet->id }}" id="hours{{ $timesheet->id }}"  class="form-control hours" onkeypress="javascript:return isNumber(event)" readonly>
                            <input type="hidden"  name="peoples" value="{{ $timesheet->peoples }}" data-id="{{ $timesheet->id }}" id="peoples{{ $timesheet->id }}" onkeypress="javascript:return isNumber(event)" class="form-control peoples">
                            <input type="hidden"  name="total_hours" value="{{ $timesheet->total_hours }}" data-id="{{ $timesheet->id }}" id="total_hours{{ $timesheet->id }}" onkeypress="javascript:return isNumber(event)" class="form-control total_hours">
                            <input type="hidden"  name="selling_cost" value="{{ $timesheet->selling_cost }}" data-id="{{ $timesheet->id }}" id="selling_cost{{ $timesheet->id }}" onkeypress="javascript:return isNumber(event)" class="form-control selling_cost">
                            <input type="hidden"  name="total_cost" value="{{ $timesheet->selling_cost }}" data-id="{{ $timesheet->id }}" id="total_cost{{ $timesheet->id }}" onkeypress="javascript:return isNumber(event)" class="form-control total_cost">
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea class="form-control" rows="2" name="notes" id="notes">{{ $timesheet->notes }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            {{ Form::submit('Update timesheet', [ 'class' => "btn btn-primary btn-flat" ]) }}
        </div>
        {{ Form::close() }}
    </div>
</section>
@stop
@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){

        $(document).on('change', '.start_time, .end_time, .peoples, .total_hours', function(){  

            var tr = $(this).parent().parent();  
            var index = tr.find('.start_time').attr('data-id');
            var start_time = $('#start_time'+index).val(); 
            var end_time = $('#end_time'+index).val();
            var hours = $('#hours'+index).val();
            var peoples = $('#peoples'+index).val();
            var total_hours = $('#total_hours'+index).val();
            var startTime = new Date('2012/10/09 '+ start_time); 
            var endTime = new Date('2012/10/10 '+ end_time);	
            var diffMs =  endTime - startTime; 
            var diifDays = Math.floor(diffMs/86400000); //days
            var diffHrs = Math.floor((diffMs%86400000)/3600000); //hours
            var diffMins = Math.floor(((diffMs%86400000)%3600000)/60000); //minutes
            
            hours=diffHrs+':'+diffMins;	
            hours=('00' + diffHrs).slice(-2)+':'+('00' + diffMins).slice(-2);
            total_hours=hours;

            $('#hours'+index).val(hours);
            $('#total_hours'+index).val(total_hours);	

        });  

            
        $(document).on('change', '.hours,.peoples,.total_hours', function(){  

            var tr = $(this).parent().parent();  
            var index = tr.find('.start_time').attr('data-id');

            var start_time = $('#start_time'+index).val(); 
            var end_time = $('#end_time'+index).val();
            var hours = $('#hours'+index).val();
            var peoples = $('#peoples'+index).val();
            var total_hours = $('#total_hours'+index).val();	
            
            total_hours=hours;

            $('#hours'+index).val(hours);
            $('#total_hours'+index).val(total_hours);

        }); 
    });
    function isNumber(evt) {
        var iKeyCode = (evt.which) ? evt.which : evt.keyCode
        if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
            return false;

        return true;
    }
</script>
@endpush