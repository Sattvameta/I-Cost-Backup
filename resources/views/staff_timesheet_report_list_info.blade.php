<div class="card-header">
    <h3 class="card-title">Staff Timesheet Report</h3>
    <div class="card-tools">
        <div class="box-tools pull-right">
            <a href="{{ route('reports.export.staff.timesheet.report', $project->id) }}" class="btn btn-primary btn-sm">Export</a>
            <a href="javascript:;" onclick="printDiv('printable-div')" class="btn btn-info btn-sm">Print</a>
        </div>
    </div>
</div>
<div class="card-body printable-div" id="printable-div">
    <table class="table table-bordered">
        <tr>
            <td colspan="5">
                <input type="button" class="btn btn-primary btn-sm" value="Expand all" title="Expand all" id="expand_all">
                <input type="button" class="btn btn-success btn-sm" value="Collaps all" title="Collaps all" id="collaps_all">
            </td>
        </tr>
        <tr>
            <td colspan="5">List of Timesheet for {{ $project->project_title }}</td>
        </tr>
        <tr>
            <th>Company</th>
            <th>Address</th>
            <th>Telephone</th>
            <th>Fax</th>
            <th>E-mail</th>
        </tr>
        <tr>
            <td>{{ $project->company->company_name }}</td>
            <td>
                {!! $project->company->address_line1 ? $project->company->address_line1.'<br>' : '' !!}
                {!! $project->company->address_line2 ? $project->company->address_line2.'<br>' : '' !!}
            </td>
            <td>{{ $project->company->phone }}</td>
            <td>{{ $project->company->fax }}</td>
            <td>{{ $project->company->email }}</td>
        </tr>
    </table>
    <div class="table-responsive">
        <table class="table table-bordered report-staff-timesheet-datatable" id="report-staff-timesheet-datatable">
            <thead>
                <tr class="table-success">
                    <th>View</th>
                    <th>Sub Code</th>
                    <th>Site Diaries</th>
                    <th>Images</th>
                    <th>Person Photo</th>
                    <th>Drawings</th>
                    <th>Total Hours</th>
                    <th>Total Cost</th>
                    <th>Estimate</th>
                    <th>Profit</th>
                    <th>Loss</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $total = 0;
                    $total_h = 0;
                    $total_p = 0;
                    $total_hp = 0;
                
                    
                    $gtotal_cost = 0;
                    $gall_total_cost = 0;
                    $gtotal_estimate = 0;
                    $gtotal_profit = 0;
                    $gtotal_loss = 0;   
                @endphp
                @if($staffTimesheets->isNotEmpty())
                    @foreach($staffTimesheets as $staffTimesheet)
                        @php 
                            $total_estimate = 0;
                            $total_cost = 0;
                            $all_total_cost = 0;
                            $all_total_hours = 0;
                            $total_profit = 0;
                            $total_loss = 0;
                            $tm = 0;
                            $profit=0;
                            $loss=0;
                            $gtotal_estimate = ($gtotal_estimate + $total_estimate);
                            $gtotal_profit = ($gtotal_profit + $total_profit);
                            $gtotal_loss = ($gtotal_loss + $total_loss);
                            $gall_total_cost = ($gall_total_cost + $all_total_cost); 

                        @endphp

                        <tr class="expandable">
                            <td class="text-align-center">
                                <input class="btn btn-success btn-sm expandable-input" type="button" value="+">
                            </td>       
                            <td>                          
                                {{ $staffTimesheet->activityOfTimesheet->item_code }}
                            </td> 
                            <td>
                                @if($staffTimesheet->timesheetFiles->where('category', 'site_diaries')->isNotEmpty())
                                    <a href="{{ route('timesheets.gallery.staff', [$staffTimesheet->id, 'category'=>'site_diaries']) }}"><i class="fas fa-file-image"></i></a>
                                @else
                                    NA
                                @endif
                            </td>
                            <td>
                                @if($staffTimesheet->timesheetFiles->where('category', 'images')->isNotEmpty())
                                    <a href="{{ route('timesheets.gallery.staff', [$staffTimesheet->id, 'category'=>'images']) }}"><i class="fas fa-file-image"></i></a>
                                @else
                                    NA
                                @endif
                            </td>
                            <td>
                                @if($staffTimesheet->timesheetFiles->where('category', 'person_photo')->isNotEmpty())
                                <a href="{{ route('timesheets.gallery.staff', [$staffTimesheet->id, 'category'=>'person_photo']) }}"><i class="fas fa-file-image"></i></a>
                                @else
                                    NA
                                @endif
                            </td>
                            <td>
                                @if($staffTimesheet->timesheetFiles->where('category', 'drawings')->isNotEmpty())
                                <a href="{{ route('timesheets.gallery.staff', [$staffTimesheet->id, 'category'=>'drawings']) }}"><i class="fas fa-file-image"></i></a>
                                @else
                                    NA
                                @endif
                            </td>
                            <td>
                                <span id="allth{{ $loop->iteration }}">{{ $staffTimesheet->total_hours }}</span>
                            </td>
                            <td><span id="alltc{{ $loop->iteration }}">&pound;{{ number_format($all_total_cost,4) }}</span></td>
                            <td><span id="allte{{ $loop->iteration }}">&pound;{{ number_format($total_estimate,4) }}</span></td>
                            <td><span id="alltp{{ $loop->iteration }}">&pound;{{ number_format($total_profit,4) }}</span></td>
                            <td><span id="alltl{{ $loop->iteration }}">&pound;{{ number_format($total_loss,4) }}</span></td>                        
                        </tr>
                        <tr>
                            <td class="text-align-center expandable" colspan="12">
                                <table class="table table-hover table-bordered table-striped table-info" align="center">
                                    <thead>
                                        <tr>
                                            <th>Activity</th>
                                            <th>Date</th>
                                            <th>Notes</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Hours</th>
                                            <th>People</th>
                                            <th>Total Hours</th>
                                            <th>Total Cost</th>
                                            <th>Estimate</th>
                                            <th>Profit</th>
                                            <th>Loss</th>    
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $detailTimesheets = $staffTimesheet->project->staffTimesheets()
                                                    ->where('sub_activity_id', $staffTimesheet->sub_activity_id)
                                                    ->where('activity_id', $staffTimesheet->activity_id)
                                                    ->where('role', 'detail')
                                                    ->get();
                                        @endphp
                                        @if($detailTimesheets->isNotEmpty())
                                            @foreach($detailTimesheets as $detailTimesheet)
                                                @php
                                                    $total_hours = 0;
                                                    $a_total_cost = 0;
                                                    $tmj = 0;
                                                    $thj = 0;
                                                    $estimate = $detailTimesheet->activityOfTimesheet->total;   
                                                    $per_estimate = $detailTimesheet->activityOfTimesheet->selling_cost;
                                                @endphp
                                                <tr>
                                                    <td>                          
                                                        {{ $detailTimesheet->activityOfTimesheet->item_code }} {{ $detailTimesheet->subActivity->sub_code }}
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                       <!-- Estimate: {{ number_format($detailTimesheet->activityOfTimesheet->quantity, 2) }}-->
                                                        Estimate: {{ number_format($detailTimesheet->activityOfTimesheet->quantity, 2) }}
                                                        <input type="hidden" id="estimate_hours{{ $loop->iteration }}" value="{{ $detailTimesheet->activityOfTimesheet->quantity }}">
                                                    </td>
                                                    <td></td> 
                                                    <td>
                                                        <b id="pt{{ $loop->iteration }}">{{ $detailTimesheet->total_hours }}</b>
                                                    </td> 
                                                    <td><b id="at{{ $loop->iteration }}">&pound;{{ number_format($total_cost, 4) }}</b></td>
                                                    <td>&pound;{{ number_format($estimate, 4) }}</td>
                                                    <td><b id="tp{{ $loop->iteration }}">&pound;{{ number_format($profit, 4) }}</b></td>
                                                    <td><b id="tl{{ $loop->iteration }}">&pound;{{ number_format($loss, 4) }}</b></td>
                                                </tr>
                                                @php
                                                    $bDetailTimesheets = $staffTimesheet->project->staffTimesheets()
                                                            ->where('sub_activity_id', $staffTimesheet->sub_activity_id)
                                                            ->where('activity_id', $detailTimesheet->activity_id)
                                                            ->where('role', 'detail')
                                                            ->get();
                                                @endphp
                                                @if($bDetailTimesheets->isNotEmpty())
                                                    @foreach($bDetailTimesheets as $bDetailTimesheet)
                                                        @php 
                                                            $per_total_cost = ($per_estimate * (float)$bDetailTimesheet->total_hours);
                                                            $tt = (float)$bDetailTimesheet->total_hours;     
                                                            $a = '';
                                                            $a = (float)$bDetailTimesheet->total_hours;
                                                            if (strpos($a, ':') !== false) {
                                                            
                                                            }else{
                                                                $tt = str_pad($tt, 2, '0', STR_PAD_LEFT).':00';
                                                            }
                                                            $dtm = new DateTime('2019-11-09 '.$tt.'');
                                                            $time = $dtm->format('H:i');
                                                            $ph = $dtm->format('H');
                                                            $pm = $dtm->format('i');
                                                            $tmin = ($ph*60)+$pm;   
                                                            $minpercost = $per_estimate/60; 
                                                            $per_total_cost = $tmin*$minpercost;
                                                        @endphp
                                                        <tr>
                                                            <td>                          
                                                                {{ $bDetailTimesheet->subActivity->sub_code }}<br>
                                                                <small>{{ $bDetailTimesheet->supervisor->full_name }}</small>
                                                            </td>
                                                            <td>                          
                                                                {{ date("d/m/Y", strtotime($bDetailTimesheet->date)) }}
                                                            </td>
                                                            <td>                          
                                                                {{ $bDetailTimesheet->notes }}
                                                            </td>
                                                            <td>
                                                                {{ $bDetailTimesheet->start_time }}
                                                            </td>
                                                            <td>
                                                                {{ $bDetailTimesheet->end_time }}
                                                            </td>
                                                            <td>
                                                                {{ $tt }}
                                                            </td>
                                                            <td>
                                                                {{ $bDetailTimesheet->peoples }}
                                                            </td> 
                                                            <td>
                                                                {{ $tt }}
                                                            </td>
                                                            <td>
                                                                &pound;{{ number_format($per_total_cost, 4) }}
                                                            </td>
                                                            <td>
                                                                &pound;{{ number_format($per_estimate, 4) }}/hr
                                                            </td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        @php
                                                            $tmj = ($tmj+$pm);
                                                            @$total_hours = ($total_hours+$ph);
                                                            @$a_total_cost = ($a_total_cost+$per_total_cost); 
                                                        @endphp
                                                    @endforeach
                                                @endif
                                                @php 
                                                    if($tmj > 60){
                                                        $total_hours = ($total_hours+floor($tmj/60));
                                                        $tmj = floor($tmj%60);
                                                    }
                                                    $total_cost = $a_total_cost;
                                                    $profit = 0;
                                                    $loss = 0;
                                                    if($total_cost > $estimate){
                                                        $loss = $total_cost - $estimate;
                                                    }
                                                    if($total_cost < $estimate){
                                                        $profit = $estimate - $total_cost;
                                                    }
                                                @endphp
                                                <script>
                                                    var dh = 0;
                                                    dh = $("#estimate_hours{{ $loop->iteration }}").val();
                                                    var th = 0;
                                                    th = {{ $total_hours }};
                                                    dh = parseFloat(dh);
                                                    var df = 0;
                                                    df = dh-th;     
                                                    if(df < 0){
                                                        $("#pt{{ $loop->iteration }}").html('<font style="color: red;">{{ $total_hours }}:{{ $tmj }}</font>');
                                                    }else{
                                                        $("#pt{{ $loop->iteration }}").html("{{ $total_hours }}:{{ $tmj }}");
                                                    } 
                                                    $("#at{{ $loop->iteration }}").html("&pound;{{ number_format($a_total_cost, 4) }}");   
                                                    $("#tp{{ $loop->iteration }}").html("&pound;{{ number_format($profit, 4) }}");
                                                    $("#tl{{ $loop->iteration }}").html("&pound;{{ number_format($loss, 4) }}");    
                                                        
                                                </script>
                                                @php
                                                    $all_total_hours = ($all_total_hours+$total_hours);  
                                                    $all_total_cost = ($all_total_cost+$total_cost);
                                                    $total_estimate = ($total_estimate+$estimate);
                                                    $tm = ($tm+$tmj);
                                                @endphp
                                            @endforeach
                                        @endif
                                        @php 
                                            if($tm > 60){
                                                $all_total_hours = ($all_total_hours+floor($tm/60));
                                                $tm = floor($tm%60);
                                            }  
                                            if($all_total_cost > $total_estimate){
                                                $total_loss = $all_total_cost - $total_estimate;
                                            }elseif($all_total_cost < $total_estimate){
                                                $total_profit = ($total_estimate - $all_total_cost);
                                            } 
                                            $gall_total_cost = ($gall_total_cost+$all_total_cost);
                                            $gtotal_estimate = ($gtotal_estimate+$total_estimate);
                                            $gtotal_profit = ($gtotal_profit+$total_profit);
                                            $gtotal_loss = ($gtotal_loss+$total_loss);
                                        @endphp
                                    </tbody>
                                </table>
                            </td>           
                        </tr>
                        <script>
                            $("#allth{{ $loop->iteration }}").html("{{ $all_total_hours }}:{{ $tm }}");
                            $("#alltc{{ $loop->iteration }}").html("&pound;{{ number_format($all_total_cost,4) }}");
                            $("#allte{{ $loop->iteration }}").html("&pound;{{ number_format($total_estimate,4) }}");
                            $("#alltp{{ $loop->iteration }}").html("&pound;{{ number_format($total_profit,4) }}");
                            $("#alltl{{ $loop->iteration }}").html("&pound;{{ number_format($total_loss,4) }}");
                        </script>
                    @endforeach
                    <tr>
                        <td colspan="7"></td>
                        <td class="text-right">Total Cost : &pound;{{ number_format($gall_total_cost,4) }}</td>
                        <td class="text-right">Estimate : &pound;{{ number_format($gtotal_estimate,4) }}</td>
                        <td class="text-right">Profit : &pound;{{ number_format($gtotal_profit,4) }}</td>
                        <td class="text-right">Loss : &pound;{{ number_format($gtotal_loss,4) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>    
    </div>
</div>