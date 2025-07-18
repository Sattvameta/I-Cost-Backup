@extends('user::layouts.master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
    
      <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('timesheets.labour', $timesheet->project_id) }}">Timesheets</a></li>
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
        {{ Form::model($timesheet, ['route' => ['timesheets.labour.update', $timesheet->id], 'method' => 'patch', 'enctype'=> 'multipart/form-data']) }}
        <input type="hidden" name="id" value="{{ $timesheet->id }}">
        <div class="card-header">
            <h3 class="card-title">Edit Timesheet</h3><hr>
            <div class="card-tools">
                <a class="btn btn-warning btn-sm" href="{{ route('timesheets.labour', $timesheet->project_id) }}" title="Back">Back</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table-responsive table table-bordered">
                <thead>
                    <tr class="table-success">
                        <th>Activity Code</th>
                        <th>Activity</th>
                        <th>Site Operative</th>
                        <th>Allocated/hr</th>
                        <th>Spent/hr</th>
                        <th>Total Spent/hr</th>
                        <th>Remaining/hr</th>
                    </tr>
                </thead>
                @php
                    $total_spent_hour = $timesheet->total_spent_hour;
                    $remaining_hour = $timesheet->remaining_hour;		
                    $spent_hour = $timesheet->spent_hour;
                        
                    $ts = $total_spent_hour;	 
                    $as = '';
                    $as = $total_spent_hour;

                    if (strpos($as, '.') !== false) {
                        $h=strtok($as, '.');
                        $m=substr(strstr($as, '.'), 1);
                        $ts=str_pad($h, 2, '0', STR_PAD_LEFT).':'.str_pad($m, 2, '0', STR_PAD_LEFT);
                        $as=$ts;
                        }else{
                        
                        }
                        
                    if (strpos($as, ':') !== false) {
                            
                    }else{
                        $ts=str_pad($ts, 2, '0', STR_PAD_LEFT).':00';	
                    }	 
                        
                    $rth=strtok($ts, ':');
                    $rtm=substr(strstr($ts, ':'), 1);
                        
                    $sth=strtok($spent_hour, ':');
                    $stm=substr(strstr($spent_hour, ':'), 1);	
                    if($rth != 00){
                        $real_total_spent_hour = abs($rth-$sth).':'.abs($rtm-$stm);	
                    }else{
                        $real_total_spent_hour = '00:00';	
                    }
                    $tt = $remaining_hour;	 
                    $a = '';
                    $a = $remaining_hour;

                    if (strpos($a, '.') !== false) {
                        $h = strtok($a, '.');
                        $m = substr(strstr($a, '.'), 1);
                        $tt = str_pad($h, 2, '0', STR_PAD_LEFT).':'.str_pad($m, 2, '0', STR_PAD_LEFT);
                        $a = $tt;
                    }else{
                    
                    } 
                    if (strpos($a, ':') !== false) {
                    }else{
                        $tt=str_pad($tt, 2, '0', STR_PAD_LEFT).':00';	
                    }	 
                        
                    $remaining_hour = $tt;
                        
                    $allocated_hour = $timesheet->allocated_hour;	
                        
                    $aa = $allocated_hour;	 
                    $al = '';
                    $al = $allocated_hour;

                    if (strpos($al, '.') !== false) {
                        $h=strtok($al, '.');
                        $m=substr(strstr($al, '.'), 1);
                        $aa=str_pad($h, 2, '0', STR_PAD_LEFT).':'.str_pad($m, 2, '0', STR_PAD_LEFT);
                        $al=$aa;
                        }else{
                        
                        }
                        
                    if (strpos($al, ':') !== false) {
                        
                    }else{
                        $aa=str_pad($aa, 2, '0', STR_PAD_LEFT).':00';	
                    }	 
                        
                    $allocated_hour = $aa;
                @endphp
                <tr>
                    <td>{{ $timesheet->activityOfTimesheet->item_code }}</td>
                    <td>{{ $timesheet->activity }}</td>
                    <td class="tr{{ $timesheet->id }}">
                        <input type="hidden" value="{{ $timesheet->peoples }}" name="peoples" data-id="{{ $timesheet->id }}" id="peoples{{ $timesheet->id }}"  class="form-control peoples">
                        {{ $timesheet->peoples }}
                    </td>
                    <td class="tr{{ $timesheet->id }}">
                        <input type="hidden" value="{{ $allocated_hour }}" name="allocated_hour" data-id="{{ $timesheet->id }}" id="allocated_hour{{ $timesheet->id }}"  class="form-control allocated_hour">
                        {{ $allocated_hour }}
                    </td>
                    <td class="tr{{ $timesheet->id }}">
                        <input type="hidden" value="{{ $timesheet->spent_hour }}" name="spent_hour" data-id="{{ $timesheet->id }}" id="spent_hour{{ $timesheet->id }}"  class="form-control spent_hour">
                        <span class="spent_hour_font" id="spent_hour_font{{ $timesheet->id }}">{{ $timesheet->spent_hour }}</span>
                    </td>
                    <td class="tr{{ $timesheet->id }}">
                        <input type="hidden" value="{{ $real_total_spent_hour }}" name="real_total_spent_hour" data-id="{{ $timesheet->id }}" id="real_total_spent_hour{{ $timesheet->id }}"  class="form-control real_total_spent_hour"> 
                        <input type="hidden" value="{{ $timesheet->total_spent_hour }}" name="total_spent_hour" data-id="{{ $timesheet->id }}" id="total_spent_hour{{ $timesheet->id }}"  class="form-control total_spent_hour">
                        <span class="total_spent_hour_font" id="total_spent_hour_font{{ $timesheet->id }}">{{ $timesheet->total_spent_hour }}</span>
                    </td>
                    <td class="tr{{ $timesheet->id }}">
                        <input type="hidden"  name="remaining_hour" value="{{ $timesheet->remaining_hour }}" data-id="{{ $timesheet->id }}" id="remaining_hour{{ $timesheet->id }}" class="form-control remaining_hour">
                        <input type="hidden"  name="total_hours" value="" data-id="{{ $timesheet->id }}" id="total_hours{{ $timesheet->id }}" onKeyPress="javascript:return isNumber(event)" class="form-control total_hours">
                        <span class="remaining_hour_font" id="remaining_hour_font{{ $timesheet->id }}">{{ $timesheet->remaining_hour }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="expandable">
                        <table class="table-responsive table table-bordered" style="width:90%;margin: auto">
                            <thead class="table-info">
                                <tr>
                                    <th>Lab Code</th>
                                    <th>Operative</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Hours</th>
                                    <th>Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($timesheet->timesheetMaterials->isNotEmpty())
                                    
                                    @foreach($timesheet->timesheetMaterials as $timesheetMaterial)
                                        <input type="hidden" name="materials[{{ $loop->iteration }}][id]" value="{{ $timesheetMaterial->id }}">
                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    {{ Form::text('materials['.$loop->iteration.'][lab_code]', $timesheetMaterial->lab_code, [
                                                        'class' => "form-control",
                                                        'id' => "lab_code",
                                                    ]) }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <select class="form-control" name="materials[{{ $loop->iteration }}][operative]">
                                                        <option value=''>Select</option>
                                                        @foreach($labour_list as $item)
                                                          <option value="{{$item->full_name}}" <?php if($timesheetMaterial->operative == $item->full_name){ ?> selected <?php } ?>>{{$item->full_name}}</option>
                                                        @endforeach
                                                    </select> 
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    {{ Form::time('materials['.$loop->iteration.'][start_time]', $timesheetMaterial->start_time, [
                                                        'class' => "form-control start_time",
                                                        'id' => "start_time".$timesheet->id.'_'.$loop->iteration,
                                                        'data-id'=> $timesheet->id.'_'.$loop->iteration
                                                    ]) }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    {{ Form::time('materials['.$loop->iteration.'][end_time]', $timesheetMaterial->end_time, [
                                                        'class' => "form-control end_time",
                                                        'id' => "end_time".$timesheet->id.'_'.$loop->iteration,
                                                        'data-id'=> $timesheet->id.'_'.$loop->iteration
                                                    ]) }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    {{ Form::text('materials['.$loop->iteration.'][hours]', $timesheetMaterial->hours, [
                                                        'class' => "form-control hours",
                                                        'id' => "hours".$timesheet->id.'_'.$loop->iteration,
                                                        'data-id'=> $timesheet->id.'_'.$loop->iteration,
                                                        'onkeypress'=>'javascript:return isNumber(event)',
                                                        'readonly'=> 'readonly'
                                                    ]) }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    {{ Form::text('materials['.$loop->iteration.'][rate]', $timesheetMaterial->rate, [
                                                        'class' => "form-control rate",
                                                        'id' => "rate".$timesheet->id.'_'.$loop->iteration,
                                                        'data-id'=> $timesheet->id.'_'.$loop->iteration,
                                                        'onkeypress'=>'javascript:return isNumber(event)'
                                                    ]) }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @isset($timesheet->note)
                                    <tr>
                                        <td colspan="6" class="text-center">Note:{{ $timesheet->note }}</td>
                                    </tr>
                                @endisset
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="7">
                        <div class="form-group @if($errors->has('notes')) has-error @endif">
                            {{ Form::label('notes', 'Notes') }}
                            {{ Form::textarea('notes', $timesheet->notes, [
                                'class' => "form-control",
                                'id' => "notes",
                                'rows'=> 3
                            ]) }}
                            @if($errors->has('notes'))
                                <span class="invalid-feedback">{{ $errors->first('notes') }}</span>
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="7">
                        <div class="form-group @if($errors->has('files.*')) has-error @endif">
                            <label for="customFile">Files</label>
                            <div class="custom-file">
                                {{ Form::file('files[]', [
                                    'class' => "custom-file-input",
                                    'id' => "files",
                                    'multiple'=> 'multiple'
                                ]) }}
                                <label class="custom-file-label" for="files"></label>
                                @if($errors->has('files.*'))
                                    <span class="invalid-feedback">{{ $errors->first('files.*') }}</span>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
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

            bsCustomFileInput.init();

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
                    var diffMs =  parseInt(endTime - startTime); 
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

                //var tr = $(this).parent().parent();  
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

        function isNumber(evt) {
            var iKeyCode = (evt.which) ? evt.which : evt.keyCode
            if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
                return false;

            return true;
        }
        
    </script>
@endpush