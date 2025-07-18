
@if($timesheets->isNotEmpty())

    @foreach($timesheets as $timesheet)
    
        @php  
            $mtotal_hours = 0;
            $mtime2 = '00:00';
            $mth = 0;
            $mtm = 0;	 
        @endphp
		
        <tr class="expandable">
            <td><input type="button" class="btn btn-primary btn-sm expandable-input" value="+"></td>
            
            @php
                $getactivity = DB::table('sub_activities')->where('id', $timesheet->sub_activity_id)->first();
            @endphp
            <td>{{$getactivity->activity}}</td>
            <td>
                @if($timesheet->timesheetFiles->where('category', 'site_diaries')->isNotEmpty())
                    <a href="{{ route('timesheets.gallery.staff', [$timesheet->id, 'category'=>'site_diaries']) }}"><i class="fas fa-file-image"></i></a>
                @else
                    NA
                @endif
            </td>
            <td>
                @if($timesheet->timesheetFiles->where('category', 'images')->isNotEmpty())
                    <a href="{{ route('timesheets.gallery.staff', [$timesheet->id, 'category'=>'images']) }}"><i class="fas fa-file-image"></i></a>
                @else
                    NA
                @endif
            </td>
            <td>
                @if($timesheet->timesheetFiles->where('category', 'person_photo')->isNotEmpty())
                <a href="{{ route('timesheets.gallery.staff', [$timesheet->id, 'category'=>'person_photo']) }}"><i class="fas fa-file-image"></i></a>
                @else
                    NA
                @endif
            </td>
            <td>
                @if($timesheet->timesheetFiles->where('category', 'drawings')->isNotEmpty())
                <a href="{{ route('timesheets.gallery.staff', [$timesheet->id, 'category'=>'drawings']) }}"><i class="fas fa-file-image"></i></a>
                @else
                    NA
                @endif
            </td>
            <td><b id="mt{{ $timesheet->id }}">{{ $timesheet->total_hours }}</b></td>
            <td width="5%">
                <a title="Print time sheet" href="{{ route('timesheets.staff.print', $timesheet->id) }}" class="btn btn-sm btn-success">
                    <i class="fas fa-print"></i>
                </a>
            </td>
        </tr>
        <tr>
            @php
                $detailTimesheets = $timesheet->project->staffTimesheets()
                        ->where('main_activity_id', $timesheet->main_activity_id)
                        ->where('sub_activity_id', $timesheet->sub_activity_id)
                        ->where('activity_id', $timesheet->activity_id)
                        ->where('role', 'detail')
                        ->groupBy('activity')
                        ->get();
                       
            @endphp
            <td colspan="8" class="expandable">
                <table class="table table-bordered" style="width:90%;margin: auto">
                    <thead class="table-info">
                        <tr>
                            <th>Activity</th>
                            <th>Date</th>
                            <th>Notes</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Hours</th>
                            <th>People</th>
                            <th>Total Hours</th>
                            <th>Approval</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($detailTimesheets->isNotEmpty())
                            @foreach($detailTimesheets as $detailTimesheet)
                            
                                @php 
                                    $total_hours = 0;
                                    $time2 = '00:00';
                                    $th = 0;
                                    $tm = 0;
                                @endphp
                                <tr>
                                    <td>{{ $detailTimesheet->activityOfTimesheet->item_code }} {{ $detailTimesheet->activity }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        Estimate: {{ number_format($detailTimesheet->activityOfTimesheet->quantity, 2) }}
                                        <input type="hidden" id="estimate_hours{{ $detailTimesheet->id }}" value="{{ number_format($detailTimesheet->activityOfTimesheet->quantity, 2) }}">
                                    </td>
                                    <td></td>
                                    <td><b id="pt{{ $detailTimesheet->id }}">{{ number_format((float)$detailTimesheet->total_hours, 2) }}</b></td>
                                    <td></td>
                                    <td>
                                        <a title="Print time sheet" href="{{ route('timesheets.staff.print', $timesheet->id) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                                @php
                                    $detailTimesheets1 = $timesheet->project->staffTimesheets()
                                            ->where('main_activity_id', $detailTimesheet->main_activity_id)
                                            ->where('sub_activity_id', $detailTimesheet->sub_activity_id)
                                            ->where('activity_id', $detailTimesheet->activity_id)
                                            ->where('activity', $detailTimesheet->activity)
                                            ->where('role', 'detail')
                                            ->get();
                                @endphp
                                @if($detailTimesheets1->isNotEmpty())
                                    @foreach($detailTimesheets1 as $detailTimesheet1)
                                        <tr>
                                            <td>{{ $detailTimesheet1->activity }} / {{ $detailTimesheet1->supervisor->full_name ?? '' }}</td>
                                            <td>{{ date("d/m/Y", strtotime($detailTimesheet1->timesheet_date)) }}</td>
                                            <td>{{ $detailTimesheet1->notes }}</td>
                                            <td>{{ $detailTimesheet1->start_time }}</td>
                                            <td>{{ $detailTimesheet1->end_time }}</td>
                                            <td>{{ $detailTimesheet1->hours }}</td>
                                            <td>{{ $detailTimesheet1->peoples }}</td>
                                            <td>{{ $detailTimesheet1->total_hours }}</td>
                                            <td>
                                                @if($detailTimesheet1->approver)
                                                    &#10004; By {{ $detailTimesheet1->approver->full_name }}
                                                    <small>{{  date("d/m/Y", strtotime($detailTimesheet1->approval_date)) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if(auth()->user()->can('access', 'timesheets add'))
                                                
                                      <a href="{{ route('timesheets.staff.edit.print', $detailTimesheet1->id) }}" class="btn btn-sm btn-success">
                                      <i class="fas fa-print" ></i>
                                      </a>
                                      
                                                    <a title="Edit time sheet" href="{{ route('timesheets.staff.edit', $detailTimesheet1->id) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a title="Delete time sheet" href="{{ route('timesheets.staff.delete', $detailTimesheet1->id) }}" onclick="return confirm('Are you sure want to remove the timesheet?')" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            @$total_hours = $total_hours + (float)$detailTimesheet1->total_hours;	
                                                
                                            $tt = (float)$detailTimesheet1->total_hours;	 
                                            $a = '';
                                            $a = (float)$detailTimesheet1->total_hours;
                                            if (strpos($a, ':') !== false) {
                                            }else{
                                                $tt=str_pad($tt, 2, '0', STR_PAD_LEFT).':00';
                                            }	 
                                            $dtm = new DateTime('2019-11-09 '.$tt.'');		 
                                            $ph = $dtm->format('H');
                                            $pm = $dtm->format('i');	 

                                            $th = $th+$ph;	 
                                            $tm = $tm+$pm; 
                                        @endphp
                                    @endforeach
                                @endif
                                @php 
                                    if($tm > 60){
                                        $th = $th + floor($tm/60);
                                        $tm = floor($tm%60);
                                    }	
                                    $th = str_pad($th, 2, '0', STR_PAD_LEFT);	 
                                    $tm = str_pad($tm, 2, '0', STR_PAD_LEFT);
                                @endphp
                                <script>
                                    var dh = $("#estimate_hours{{ $detailTimesheet->id }}").val();
                                    var th = "{{ $th }}";	
                                    dh = parseFloat(dh).toFixed(2);
                                    th = parseFloat(th).toFixed(2);
                                    if(dh < th){	
                                        $("#pt{{ $detailTimesheet->id }}").html("{{ $th }}");		
                                    }
                                    else{
                                        $("#pt{{ $detailTimesheet->id }}").html("{{ $th }}:{{ $tm }}");		
                                    }

                                </script>
                                @php
                                    $mth = $mth+$th;	 
                                    $mtm = $mtm+$tm;		  
                                    
                                @endphp
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </td>
        </tr>
        @php

            if($mtm>60){
                $mth=$mth+floor($mtm/60);
                $mtm=floor($mtm%60);
            }	

            $mth=str_pad($mth, 2, '0', STR_PAD_LEFT);	 
            $mtm=str_pad($mtm, 2, '0', STR_PAD_LEFT);		  
            
        @endphp
        <script>$("#mt{{ $timesheet->id }}").html("{{ $mth }}:{{ $mtm }}");</script>
    @endforeach
@endif