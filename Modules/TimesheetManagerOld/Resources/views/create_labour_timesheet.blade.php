@extends('user::layouts.master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
     
      <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('timesheets.labour', $project->id) }}">Timesheets</a></li>
          <li class="breadcrumb-item active">Create Timesheet</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    @include('layouts.flash.alert')
    <div class="card">
        {{  Form::open(array('route' => ['timesheets.labour.store', $project->id],'method' => 'post', 'id' => 'form-timesheet-approved')) }}
        <!--{{ Form::open(['route' => ['timesheets.labour.store', $project->id], 'method' => 'post', 'enctype'=> 'multipart/form-data']) }}-->
        <div class="card-header">
            <h3 class="card-title">Create Timesheet</h3><hr>
            <div class="card-tools">
                <input type="hidden" name="pro_id" class="pro_id" value="{{$project->id}}">
                <a class="btn btn-primary btn-sm" href="{{ route('timesheets.labour', $project->id) }}" title="Back">Back</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::label('area', 'Area') }}
                        {{ Form::select('area', $areas, null, [
                            'class' => "form-control multiselect-dropdown areas",
                            'id' => "areas",
                            'data-live-search'=>'true',
                            'onchange'=> 'getLevels(this.value)'
                        ]) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::label('level', 'Level') }}
                        {{ Form::select('level', [''=>'Select level'], old('level'), [
                            'class' => "form-control multiselect-dropdown levels",
                            'id' => "levels",
                            'data-live-search'=>'true',
                            'onchange'=> 'getSubCodes(this.value)'
                        ]) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::label('sub_code', 'Sub code') }}
                        {{ Form::select('sub_code', [''=>'Select sub code'], null, [
                            'class' => "form-control multiselect-dropdown sub_code",
                            'id' => "sub_code",
                            'data-live-search'=>'true',
                            'onchange'=> 'prepareTimesheetForm(this.value)'
                        ]) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Date</label>            
                     <div class="inner-addon right-addon">
                        <i class="fa fa-calendar-alt open-datetimepicker" aria-hidden="true"></i>
                        <input type="text" class="form-control"  id="date" name="date" placeholder="Select Date" style="cursor: pointer;" />
                    </div>
                    <style>
                        /* enable absolute positioning */
                    .inner-addon { 
                        position: relative; 
                    }
                    
                    /* style icon */
                    .inner-addon .open-datetimepicker {
                      position: absolute;
                      padding: 10px;
                      pointer-events: none;
                    }
                    
                    /* align icon */
                    .left-addon .open-datetimepicker  { left:  0px;}
                    .right-addon .open-datetimepicker { right: 0px;}
                    
                    /* add padding  */
                    .left-addon input  { padding-left:  30px; }
                    .right-addon input { padding-right: 30px; }
                    </style>
                                  
                    <script src="{{asset('plugins/date_picker/js/jquery.min.js')}}"></script> 
                    
                    <script src="{{asset('plugins/date_picker/js/bootstrap-datepicker.js')}}"></script>
                    <link href="{{asset('plugins/date_picker/css/bootstrap-datepicker.css')}}" rel="stylesheet"/>
                    
                    
                    
                    <script>
                    
                      $("#date").datepicker({
                        format: 'd-M-yyyy',
                        inline: false,
                    	
                        step: 5,
                        multidate: 5,
                        closeOnDateSelect: true
                    
                    });
                    </script> 
                        <!--{{ Form::label('date', 'Date') }}
                        {{ Form::date('date', date('Y-m-d'), [
                            'class' => "form-control",
                            'id' => "date"
                        ]) }}-->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 timesheet-form-wrapper">

                </div>
            </div>
        </div>
        <div class="card-footer">
            {{ Form::submit('Create timesheet', [ 'class' => "btn btn-primary btn-flat" ]) }}
        </div>
        {{ Form::close() }}
    </div>
</section>
@stop
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            
            $('#form-timesheet-approved').on('submit', function (e) {
                e.preventDefault();
                var project_id = $('.pro_id').val();
                /*Ajax Request Header setup*/
                $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
                });
                var host = "{{URL::to('/')}}";
                $.ajax({
                    type: "POST",
                    url: host+'/timesheets/labour/'+project_id+'/storeseperate',
                    data: $('#form-timesheet-approved').serialize(),
                    success: function (data) {
                        alert("This timesheet has been created successfully!");
                    }
                });
                return false;
            });
        
            var mainActivityId = $(document).find('.areas').val();
            if(mainActivityId){
                getLevels(mainActivityId);
            }

            $(document).on('click', '.select_all', function(){
                if (this.checked) {
                    $('.checkbox').each(function() {
                        this.checked = true;
                        $(".tr" + this.value).css("opacity", "1");
                    });
                } else {
                    $('.checkbox').each(function() {
                        this.checked = false;
                        $(".tr" + this.value).css("opacity", "0.2");
                    });
                }
            });
            $(document).on('click', '.selected_rows', function(){
                if (this.checked == true) {
                    $(".tr" + this.value).css("opacity", "1");
                } else {
                    $(".tr" + this.value).css("opacity", "0.2");
                }
                if ($('.checkbox:checked').length == $('.checkbox').length) {
                    $('#select_all').prop('checked', true);
                } else {
                    $('#select_all').prop('checked', false);
                }
            });

            $(document).on('change', '.start_time, .end_time', function(){  

                var index = $(this).attr('data-id');

                var start_time = $('#start_time'+index).val(); 
                var end_time = $('#end_time'+index).val();
                var hours = $('#hours'+index).val();
                var peoples = $('#peoples'+index).val();
                var total_hours = $('#total_hours'+index).val();
                var th = 0;
                var tm = 0;	

                var startTime   = new Date('2012/10/09 '+ start_time); 
                var endTime     = new Date('2012/10/10 '+ end_time);	
                var diffMs      =  endTime - startTime; 
                var diifDays    = Math.floor(diffMs/86400000); //days
                var diffHrs     = Math.floor((diffMs%86400000)/3600000); //hours
                var diffMins    = Math.floor(((diffMs%86400000)%3600000)/60000); //minutes
                    
                hours = diffHrs+':'+diffMins;	
                hours = ('00' + diffHrs).slice(-2)+':'+('00' + diffMins).slice(-2);
                total_hours = hours;

                $('#hours'+index).val(hours);
                $('#total_hours'+index).val(total_hours);	
                    
                var spent_hour='';	
                var rowid = index.substr(0,index.indexOf('_'));
                var total_spent_hour = $('#real_total_spent_hour'+rowid).val();
                var peoples = $('#peoples'+rowid).val();
                
                for(var j = 1; j <= peoples; j++){
                    var ind = rowid+'_'+j;
                    var start_time = $('#start_time'+ind).val(); 
                    var end_time = $('#end_time'+ind).val();
                    var hours = $('#hours'+ind).val();
                    var startTime = new Date('2012/10/09 '+ start_time); 
                    var endTime = new Date('2012/10/10 '+ end_time);	
                    var diffMs =  endTime - startTime; 
                    var diifDays = Math.floor(diffMs/86400000); //days
                    var diffHrs = Math.floor((diffMs%86400000)/3600000); //hours
                    var diffMins = Math.floor(((diffMs%86400000)%3600000)/60000); //minutes
                    
                    hours = diffHrs+':'+diffMins;	
                    hours = ('00' + diffHrs).slice(-2)+':'+('00' + diffMins).slice(-2);
                    total_hours = hours;// * peoples;	

                    th = th+diffHrs;
                    tm = tm+diffMins;
                }

                if(tm > 60){
                    th = th+Math.floor(tm/60);
                    tm = Math.floor(tm%60);
                }	
                spent_hour = th+':'+tm;	
                    
                $('#spent_hour'+rowid).val(spent_hour);	
                $('#spent_hour_font'+rowid).html(spent_hour);	
                        
                var t_th = Math.abs(total_spent_hour.substr(0,total_spent_hour.indexOf(':')));
                var t_tm = Math.abs(total_spent_hour.substr(total_spent_hour.indexOf(':')+1));
                t_th = t_th+th;
                t_tm = t_tm+tm;	
                    
                if(t_tm > 60){
                    t_th = t_th+Math.floor(t_tm/60);
                    t_tm = Math.floor(t_tm%60);
                }
                total_spent_hour = t_th+':'+t_tm;	
                    
                $('#total_spent_hour'+rowid).val(total_spent_hour);	
                $('#total_spent_hour_font'+rowid).html(total_spent_hour);		
                    
                var allocated_hour = $('#allocated_hour'+rowid).val();
                    
                var allocated_hour_th = Math.abs(allocated_hour.substr(0,allocated_hour.indexOf(':')));
                var allocated_hour_tm = Math.abs(allocated_hour.substr(allocated_hour.indexOf(':')+1));

                var remaining_hour_th = Math.abs(allocated_hour_th - t_th);	
                var remaining_hour_tm = Math.abs(allocated_hour_tm - t_tm);	

                var remaining_hour = remaining_hour_th+':'+ remaining_hour_tm;	
                    
                $('#remaining_hour'+rowid).val(remaining_hour);
                $('#remaining_hour_font'+rowid).html(remaining_hour);	

            });  

                    
            $(document).on('change', '.hours, .peoples, .total_hours', function(){  

                var index = $(this).attr('data-id');
                var start_time = $('#start_time'+index).val(); 
                var end_time = $('#end_time'+index).val();
                var hours = $('#hours'+index).val();
                var peoples = $('#peoples'+index).val();
                var total_hours = $('#total_hours'+index).val();	
                    
                total_hours = hours;// * peoples;
                $('#hours'+index).val(hours);
                $('#total_hours'+index).val(total_hours);

            });

        });
        function getLevels(mainActivityId){

            projectId = "{{ $project->id }}";

            var html = "<option value=''>Select level</option>";
            var route = "{{ route('ajax.levels.list') }}";
            route +="?project_id="+projectId+"&main_activity_id="+mainActivityId;
            $.get(route, function(data){
                for (var key of Object.keys(data)) {
                    var mainId = "{{ old('area') }}";
                    if(mainId == key){
                        html = html+"<option value='"+key+"' selected>"+data[key]+"</option>";
                    }else{
                        html = html+"<option value='"+key+"' selected>"+data[key]+"</option>";
                    }
                }
                $(document).find('.levels').html(html);
                $(document).find('.levels').trigger('change');
            });
            }
            function getSubCodes(mainActivityId){
            var html = "<option value=''>Select sub code</option>";
            var route = "{{ route('ajax.sub.codes.list') }}";
            route +="?main_activity_id="+mainActivityId;
            
           
            $.get(route, function(data){
                if(Object.keys(data).length == 1){
                    for (var key of Object.keys(data)) {
                        var subId = "{{ old('sub_code') }}";
                        if(subId == key){
                            html = html+"<option value='"+key+"' selected>"+data[key]+"</option>";
                        }else{
                            html = html+"<option value='"+key+"' selected>"+data[key]+"</option>";
                        }
                    }
                }else{
                    for (var key of Object.keys(data)) {
                        var subId = "{{ old('sub_code') }}";
                        if(subId == key){
                            html = html+"<option value='"+key+"' selected>"+data[key]+"</option>";
                        }else{
                            html = html+"<option value='"+key+"'>"+data[key]+"</option>";
                        }
                    }
                    
                }
                $(document).find('.sub_code').html(html);
                $(document).find('.sub_code').trigger('change');
            });
            }
        function prepareTimesheetForm(subActivityId){
            var route = "{{ route('timesheets.ajax.labour.timesheet.form') }}";
            route +="?sub_activity_id="+subActivityId;
            $.get(route, function(data){
                $(document).find('.timesheet-form-wrapper').html(data.html);
            });
        }
        function prepareLaboursForm(peoples, itemCode,  activityId){
            var labours = "";
            var time = "{{ date('H:i') }}";
          
            for(var i = 1; i <= peoples; i++){
                labours +='<tr><td><input type="text" name="activities['+activityId+'][materials]['+i+'][lab_code]" value="'+itemCode+'-'+i+'" class="form-control lab_code"></td><td><select  name="activities['+activityId+'][materials]['+i+'][operative]" class="form-control operative"><option></option>@foreach ($labour_list as $list) <option value="{{ $list->full_name }}">{{ucfirst($list->full_name)}}</option> @endforeach</select></td><td><input step="any" type="time" value="'+time+'" name="activities['+activityId+'][materials]['+i+'][start_time]" data-id="'+activityId+'_'+i+'" id="start_time'+activityId+'_'+i+'"  class="form-control start_time"></td><td><input step="any" type="time" value="'+time+'" name="activities['+activityId+'][materials]['+i+'][end_time]" data-id="'+activityId+'_'+i+'" id="end_time'+activityId+'_'+i+'"  class="form-control end_time"></td><td><input type="text"  name="activities['+activityId+'][materials]['+i+'][hours]" value="" data-id="'+activityId+'_'+i+'" id="hours'+activityId+'_'+i+'"  class="form-control hours" readonly="readonly" onkeypress="javascript:return isNumber(event)"></td><td><input type="text"  name="activities['+activityId+'][materials]['+i+'][rate]" value="0" data-id="'+activityId+'_'+i+'" id="rate'+activityId+'_'+i+'"  class="form-control rate" onkeypress="javascript:return isNumber(event)"></td></tr>';
            }
	        $(document).find('.labour_table'+activityId).html('<table id="labour_table'+activityId+'" class="table-responsive table table-bordered" style="width:90%;margin:auto"><thead class="table-info"><tr><th>Lab Code</th><th>Operative</th><th>Start time</th><th>End time</th><th>Hours</th><th>Rate</th></tr></thead><tbody>'+labours+'</tbody></table>');
            $(document).find('.notes_row'+activityId).show();
            $(document).find('.files_row'+activityId).show();

            $('#total_hours'+activityId).val(0.0);	
            $('#real_total_spent_hour'+activityId).val(0.0);
            $('#spent_hour'+activityId).val(0.0);	
            $('#spent_hour_font'+activityId).html('0.0');	
            $('#total_spent_hour'+activityId).val(0.0);	
            $('#total_spent_hour_font'+activityId).html('0.0');	 
            var allocated_hour = $('#allocated_hour'+rowid).val();   
            var remaining_hour_th = 0;	
            var remaining_hour_tm = 0;	  
            var allocated_hour_th = Math.abs(allocated_hour.substr(0,allocated_hour.indexOf(':')));
            var allocated_hour_tm = Math.abs(allocated_hour.substr(allocated_hour.indexOf(':')+1));
            var remaining_hour = remaining_hour_th+':'+ remaining_hour_tm;   
            $('#remaining_hour'+activityId).val(remaining_hour);
            $('#remaining_hour_font'+activityId).html(remaining_hour);
        }
        function isNumber(evt) {
            var iKeyCode = (evt.which) ? evt.which : evt.keyCode
            if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
                return false;

            return true;
        }
    </script>
@endpush