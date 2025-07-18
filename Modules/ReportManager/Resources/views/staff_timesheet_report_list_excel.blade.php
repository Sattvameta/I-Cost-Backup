
<div class="card-body printable-div" id="printable-div">
    <table class="table-responsive table-responsive table table-bordered">
        
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
	
        <table class="table-responsive table table-bordered report-staff-timesheet-datatable" id="report-staff-timesheet-datatable">
           
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

                       
                        <tr>
                            <td class="text-align-center expandable" colspan="12">
                                <table class="table-responsive table table-hover table-bordered table-striped table-info" align="center">
								 <thead>
                <tr class="table-success">
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
								 <tr class="expandable">
                            
                            <td>                          
                                {{ $staffTimesheet->activityOfTimesheet->item_code }}
                            </td> 
                            <td>
                               
                            </td>
                            <td>
                               
                            </td>
                            <td>
                               
                            </td>
                            <td>

                            </td>
                            <td>
                                <span id="allth{{ $loop->iteration }}">{{ $staffTimesheet->total_hours }}</span>
                            </td>
                            <td><span id="alltc{{ $loop->iteration }}">{{ number_format($all_total_cost,4) }}</span></td>
                            <td><span id="allte{{ $loop->iteration }}">{{ number_format($total_estimate,4) }}</span></td>
                            <td><span id="alltp{{ $loop->iteration }}">{{ number_format($total_profit,4) }}</span></td>
                            <td><span id="alltl{{ $loop->iteration }}">{{ number_format($total_loss,4) }}</span></td>                        
                        </tr>
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
                                                    
                                                    <td>
                                                       <!-- Estimate: {{ number_format($detailTimesheet->activityOfTimesheet->quantity, 2) }}-->
                                                        Estimate: {{ number_format($detailTimesheet->activityOfTimesheet->quantity, 2) }}
                                                    </td>
                                                   
                                                    <td>
                                                       {{ $detailTimesheet->total_hours }}
                                                    </td> 
                                                    <td>{{ number_format($total_cost, 4) }}</td>
                                                    <td>{{ number_format($estimate, 4) }}</td>
                                                    <td>{{ number_format($profit, 4) }}</td>
                                                    <td>{{ number_format($loss, 4) }}</td>
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
                                                                {{ number_format($per_total_cost, 4) }}
                                                            </td>
                                                            <td>
                                                               {{ number_format($per_estimate, 4) }}/hr
                                                            </td>
                                                           
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
                        
                    @endforeach
                    <tr>
                        <td colspan="7"></td>
                        <td class="text-right">Total Cost :{{ number_format($gall_total_cost,4) }}</td>
                        <td class="text-right">Estimate : {{ number_format($gtotal_estimate,4) }}</td>
                        <td class="text-right">Profit : {{ number_format($gtotal_profit,4) }}</td>
                        <td class="text-right">Loss : {{ number_format($gtotal_loss,4) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>    
    </div>
</div>
