<div>&nbsp;</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive printable-div" id="printable-div">
            <table class="table-responsive table table-bordered">
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
            <table class="table-responsive table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th style="min-width: 200px;">Project </th>
                        <th style="min-width: 100px;">Code</th>
                        <th style="min-width: 150px;">Role</th>
                        <th style="min-width: 150px;">Date</th>
                        <th style="min-width: 150px;">Name</th>
                        <th style="min-width: 150px;">Activity</th>
                        <th style="min-width: 150px;">Total Hours</th>
                        <th style="min-width: 150px;">Total Cost</th>
                        <th style="min-width: 150px;">Estimate</th>
                        <th style="min-width: 150px;">Profit</th>
                        <th style="min-width: 150px;">Loss</th>
                        <th style="min-width: 150px;">Approved</th>       
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $total = 0;
                        $total_h = 0;
                        $total_p = 0;
                        $total_hp = 0;
                    $a_total_all = 0;
                        $total_estimate = 0;
                        $total_cost = 0;
                        $all_total_cost = 0;
                        $total_profit = 0;
                        $total_loss = 0;
            
                        $gtotal_cost = 0;
                        $gall_total_cost = 0;
                        $gtotal_estimate = 0;
                        $gtotal_profit = 0;
                        $gtotal_profits = 0;
                        $gtotal_loss = 0;
                        $gtotal_losss = 0;
            
                        $total_estimate = 0;
                        $total_cost = 0;
                        $all_total_cost = 0;
                        $all_total_hours = 0;
                        $total_profit = 0;
                        $total_loss = 0;	
                        
                        $th = 0;
                        $tm = 0;
                    @endphp
                    @if($timesheets->isNotEmpty())
                        @foreach($timesheets as $timesheet)
                            @php
                                $total_hours = 0;
                                $total_hourss = 0;
                                $total_all = 0;
                                
                                $total_estimate_Row =0;
                                $tmjs = 0; 
                                $a_total_cost = 0;
                                $thj = 0;
                                $tmj = 0; 
                                $estimate = $timesheet->activityOfTimesheet->total;	
                                $per_estimate = $timesheet->activityOfTimesheet->selling_cost;
                                $total_h = ($total_h + (float)$timesheet->hours);
                                $total_p = ($total_p + $timesheet->peoples);
                                $total_hp = ($total_hp + (float)$timesheet->total_hours);
                                $profit = 0;
                                $loss = 0;
                            @endphp
                            <tr>
                                <td>{{ $timesheet->project->project_title }}</td>
                                    <td>{{ $timesheet->activityOfTimesheet->item_code }}</td>
                                    <td></td>
                                    <td></td>
                                <td></td>
                                <td>
                                        <!--Estimate: {{ number_format($timesheet->quantity) }}hr</p>-->
                                        Estimate: {{ number_format($timesheet->activityOfTimesheet->quantity) }}hr</p>
                                        <input type="hidden" id="estimate_hours{{ $loop ->iteration }}" value="{{ $timesheet->quantity }}">
                                </td>                   
                                <td class="text-right"><span id="pt{{ $loop ->iteration }}">{{ $total_hours }}</span></td>
                                <td class="text-right"><!--<span id="at{{ $loop ->iteration }}">&pound;{{ number_format($total_cost,4) }}</span>-->
                                <span id="atnm{{ $loop ->iteration }}">&pound;{{ number_format($total_all,2) }}</span>
                                </td>
                                <!--<td></td>-->
                                <td class="text-right"><span>&pound;{{ number_format($estimate,2) }}</span></td>
                                <!--<td></td>-->
                                <td class="text-right"><span id="tp{{ $loop ->iteration }}">&pound;{{ number_format($profit,2) }}</span></td>
                                <!--<td></td>-->
                                <td class="text-right"><span id="tl{{ $loop ->iteration }}">&pound;{{ number_format($loss,2) }}</span></td>
                                <!--<td></td>-->
                                <td></td>
                            </tr>
                            @php 
                                $timesheets1 = $project->staffTimesheets()
                                                    ->where('role', 'detail')
                                                    ->where('activity_id', $timesheet->activity_id);
                                if($from && $to){
                                    $timesheets1 = $timesheets1->whereBetween('timesheet_date', [$from, $to]);
                                }
                                $timesheets1 = $timesheets1->orderBy('id', 'DESC')->get();
                            @endphp
                            @if($timesheets1->isNotEmpty())
                                @foreach($timesheets1 as $timesheet1)
                                    @php 
                                        $per_total_cost = ($per_estimate * (float)$timesheet1->total_hours);	
            
                                        $tt = (float)$timesheet1->total_hours;	 
                                        $a = '';
                                        $a = (float)$timesheet1->total_hours;
                                        if (strpos($a, ':') !== false) {
                                            
                                        }else{
                                            $tt = str_pad($tt, 2, '0', STR_PAD_LEFT).':00';
                                        }  
                                        $dtm = new DateTime('2019-11-09 '.$tt.'');
                                        $time = $dtm->format('H:i');
                                        $ph = $dtm->format('H');
                                        $pm = $dtm->format('i');
                                        $tmin = (($ph*60)+$pm);	
                                        $minpercost = $per_estimate/60;	
                                        $per_total_cost = $tmin*$minpercost;
                                    @endphp
                             
                                    <tr>	
                                        <td>{{ $timesheet1->project->project_title }}</td>
                                        <td>{{ $timesheet1->activityOfTimesheet->item_code }}</td>
                                        <td>{{ $timesheet1->activityOfTimesheet->mhr_role }}</td>
                                        <td>{{ date("d/m/Y", strtotime($timesheet1->timesheet_date)) }}</td>
                                        <td>{{ $timesheet->supervisor->full_name }}</td>
                                        <td>{{ $timesheet->subActivity->sub_code }}</td>
                                        <td class="text-right">{{ $tt }}</td>
                                        <td class="text-right">&pound;{{ number_format($per_total_cost,2) }}</td>
                                        <td class="text-right">&pound;{{ number_format($per_estimate,2) }}/hr</td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td>
                                            @if($timesheet1->approver)
                                                &#10004;{{ $timesheet1->approver->full_name }}<br/><small>{{ date("d/m/Y", strtotime($timesheet1->approval_date)) }}</small>
                                            @endif
                                        </td>
                                    </tr>
                                    
                                    @php 
                                      
                                        $tmjs = $tmjs+$pm;
                                        @$total_hourss = ($total_hourss + $ph);
                                        @$total_all = ($total_all + $per_total_cost);
                                        @$total_estimate_Row = ($total_estimate_Row + $per_estimate);
                                     
                                       
                                    @endphp
                             
                                @endforeach 
                                @php 
                                    $tmj = $tmj+$pm;
                                    @$total_hours = ($total_hours + $ph);
                                    @$a_total_cost = ($a_total_cost + $per_total_cost);
                                    @$a_total_all = ($a_total_all + $total_all);
                                    
                                @endphp   
                            @endif
                            @php 
                                if($tmj  >60){
                                    $total_hours = $total_hours+floor($tmj/60);
                                    $tmj = floor($tmj%60);
                                }
                                if($tmjs  >60){
                                    $total_hourss = $total_hourss+floor($tmjs/60);
                                    $tmjs = floor($tmjs%60);
                                }
                                $total_cost = $a_total_cost;
                                $profit = 0;
                                $loss = 0;
                                if($total_cost > $estimate){
                                $loss=$total_cost - $estimate;
                                }
                                if($total_cost < $estimate){
                                $profit= $estimate - $total_cost;
                                }
                                
                                
                                $profits = 0;
                                $losss = 0;
                                if($total_all > $estimate){
                                $losss=$total_all - $estimate;
                                }
                                if($total_all < $estimate){
                                $profits= $estimate - $total_all;
                                }
                            @endphp
                            <script>
                                var dh = 0;
                                dh = $("#estimate_hours{{ $loop->iteration }}").val();
                                var th = 0;
                                th = "{{ $total_hours }}";
                                dh = parseFloat(dh);
                                var df = 0;
                                df = dh-th;		
                                /*if(df < 0){
                                    $("#pt{{ $loop->iteration }}").html('<font style="color: red;">{{ $total_hours }}:{{ $tmj }}</font>');
                                }else{
                                    $("#pt{{ $loop->iteration }}").html("{{ $total_hours }}:{{ $tmj }}");
                                } */ 
                                if(df < 0){
                                    $("#pt{{ $loop->iteration }}").html('<font style="color: red;">{{ $total_hourss }}:{{ $tmjs }}</font>');
                                }else{
                                    $("#pt{{ $loop->iteration }}").html("{{ $total_hourss }}:{{ $tmjs }}");
                                } 
                                $("#atnm{{ $loop->iteration }}").html("&pound;{{ number_format($total_all,2) }}");
                                $("#at{{ $loop->iteration }}").html("&pound;{{ number_format($a_total_cost,2) }}");
                                /*$("#tp{{ $loop->iteration }}").html("&pound;{{ number_format($profit,2) }}");*/
                                
                                $("#tp{{ $loop->iteration }}").html("&pound;{{ number_format($profits,2) }}");
                                /*$("#tl{{ $loop->iteration }}").html("&pound;{{ number_format($loss,2) }}");*/
                                $("#tl{{ $loop->iteration }}").html("&pound;{{ number_format($losss,2) }}");
                            </script>
                            @php 
                                $all_total_hours = ($all_total_hours+$total_hours);  
                                $all_total_cost = ($all_total_cost+$total_cost);
                                $total_estimate = ($total_estimate+$estimate);
                                $tm = ($tm+$tmj); 
                                $gtotal_profits = ($gtotal_profits+$profits);
                                $gtotal_losss = ($gtotal_losss+$losss);
                            @endphp
                        @endforeach
                        @php 
                            if($all_total_cost > $total_estimate){
                                $total_loss = ($all_total_cost - $total_estimate);
                            }elseif($all_total_cost < $total_estimate){
                                $total_profit = ($total_estimate - $all_total_cost);
                            }
                            $gall_total_cost = ($gall_total_cost+$all_total_cost);
                            $gtotal_estimate = ($gtotal_estimate+$total_estimate);
                            $gtotal_profit = ($gtotal_profit+$total_profit);
                            
                            $gtotal_loss = ($gtotal_loss+$total_loss);
                            
                        @endphp
                        <tr class="text-right">
                            <td  colspan="7"><b>Totals : </b></td>
                            <!--<td ><b> &pound;{{ number_format($gall_total_cost,2) }}</b></td>-->
                            <td ><b> &pound;{{ number_format($a_total_all,2) }}</b></td>
                            <td ><b> &pound;{{ number_format($gtotal_estimate,2) }}</b></td>
                            <!--<td ><b> &pound;{{ number_format($total_estimate_Row,2) }}</b></td>-->
              
                            <td ><!--<b>&pound;{{ number_format($profits,2) }}---<b>-->&pound;{{ number_format($gtotal_profits,2) }}</b></b></td>
                        
                            <td ><!--<b>&pound;{{ number_format($losss,2) }}--<b>-->&pound;{{ number_format($gtotal_losss,2) }}</b></b></td>
                            <td></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
