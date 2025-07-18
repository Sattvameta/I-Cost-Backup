  
    <div class="card-body">                              

            <button onclick="printDiv('printable-div')" class="mb-2 mr-2 btn-icon-vertical btn btn-primary">
            <i class="pe-7s-print btn-icon-wrapper"></i>Print
            </button>
              
              <button onclick="window.location.href='{{ route('reports.export.labour.timesheet.report', $project->id) }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
            <i class="pe-7s-cloud-upload btn-icon-wrapper"></i>Export
            </button>
    </div>
<div class="card-header">
    <h3 class="card-title">Labour Timesheet Report</h3>
  
    
  
</div>
<div class="card-body printable-div" id="printable-div">

    <table class="table-responsive table table-bordered">
        <tr>
            <td colspan="5">
                <input type="button" class="btn btn-primary btn-sm" value="Expand all" title="Expand all" id="expand_all">
                <input type="button" class="btn btn-success btn-sm" value="Collapse all" title="Collapse all" id="collaps_all">
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
	<div class="col-md-3">
				<div class="form-group">
						<label>Search</label><br>
							<input id="myInput" type="text">
				</div>
			</div>
        <table class="table-responsive table table-bordered report-labour-timesheet-datatable" id="report-labour-timesheet-datatable">
            <thead>
                <tr class="table-success">
                    <th>View</th>
                    <th>Sub Code</th>
                    <th>Activity Code</th>
                    <th>Activity</th>
                    <th>Allocated/Hr</th>
                    <th>Total spent/hr</th>
                    <th>Remaining/hr</th>
                    <th>Total cost</th>
                    <th>Estimate</th>
                    <th>Profit</th>
                    <th>Loss</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $all_total_hourss =0;
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
                @if($labourTimesheets->isNotEmpty())
                    @foreach($labourTimesheets as $labourTimesheet)
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
                                {{ $labourTimesheet->subActivity->sub_code }}
                            </td>    	
                            <td>						  
                                {{ $labourTimesheet->activityOfTimesheet->item_code }}
                            </td>
                            <td>						  
                                {{ $labourTimesheet->activityOfTimesheet->activity }}
                            </td>
                            <td>
                                {{ $labourTimesheet->allocated_hour }}
                            </td>
                            <td>
								<span id="allth{{ $loop->iteration }}">{{ $labourTimesheet->total_spent_hour }}</span>
							
						    </td>
	                        <td>
	                            <span id="allrhs{{ $loop->iteration }}">{{ $labourTimesheet->remaining_hour }}</span>
							   <!--<span id="allrh{{ $loop->iteration }}">{{ $labourTimesheet->remaining_hour }}</span>-->
							  
						   </td>
                            <td><span id="alltc{{ $loop->iteration }}">&pound;{{ number_format($all_total_cost,2) }}</span></td>
                            <td><span id="allte{{ $loop->iteration }}">&pound;{{ number_format($total_estimate,2) }}</span></td>
                            <td><span id="alltp{{ $loop->iteration }}">&pound;{{ number_format($total_profit,2) }}</span></td>
                            <td><span id="alltl{{ $loop->iteration }}">&pound;{{ number_format($total_loss,2) }}</span></td>						
                        </tr>
                        <tr>
                            <td class="text-align-center expandable" colspan="12">
                                <table class="table-responsive table table-hover table-bordered table-striped table-info" align="center">
                                    <thead>
                                        <tr>
                                            <th>Lab code</th>
                                            <th>Date</th>
                                            <th>Operative</th>
                                            <th>Start time</th>
                                            <th>End time</th>
                                            <th>Peoples</th>
                                            <th>Spent/hr</th>
                                            <th>Rate</th>
                                            <th>Total cost</th>
                                            <!--<th>Estimate</th>
                                            <th>Profit</th>
                                            <th>Loss</th>-->    
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $labourTimesheets1 = $labourTimesheet->project->labourTimesheets()
                                                    ->where('activity_id', $labourTimesheet->activity_id)
                                                    ->get();
                                        @endphp
                                      
                                        @if($labourTimesheets1->isNotEmpty())
                                            @foreach($labourTimesheets1 as $labourTimesheet1)
                                                @php
                                                    $total_hours = 0;
                                                    $a_total_cost = 0;
                                                    $tmj = 0;
                                                    $thj = 0;
                                                    $estimate = $labourTimesheet1->activityOfTimesheet->total;	
                                                    $per_estimate = $labourTimesheet1->activityOfTimesheet->selling_cost;
                                                @endphp
                                                <tr>
                                                    <td>						  
                                                        {{ $labourTimesheet1->activityOfTimesheet->item_code }}
                                                    </td>
                                                    <td><!--{{ date("d/m/Y", strtotime($labourTimesheet1->timesheet_date)) }}--></td>
                                                    <td></td>
                                                    <td></td>
                                                   
                                                    <td>
                                                        Estimate: {{ number_format($labourTimesheet1->activityOfTimesheet->quantity, 2) }}
                                                        <input type="hidden" id="estimate_hours{{ $loop->iteration }}" value="{{ $labourTimesheet1->activityOfTimesheet->quantity }}">
                                                    </td>
                                                    <td>{{ $labourTimesheet1->peoples }}</td> 
                                                    <td>
                                                        <b id="pt{{ $loop->iteration }}">{{ $labourTimesheet1->spent_hour }}</b>
                                                    </td>
                                                    <!-- <td></td>-->
                                                    <td><b id="rt{{ $loop->iteration }}">&nbsp;</b></td> 
                                                    <td><!--<b id="at{{ $loop->iteration }}">&pound;{{ number_format($total_cost, 2) }}</b>--></td>
                                               
                                                    
                                                    <!--<td>&pound;{{ number_format($estimate, 2) }}</td>
                                                    <td><b id="tp{{ $loop->iteration }}">&pound;{{ number_format($profit, 2) }}</b></td>
                                                    <td><b id="tl{{ $loop->iteration }}">&pound;{{ number_format($loss, 2) }}</b></td>-->
                                                </tr>
                                                @if($labourTimesheet1->timesheetMaterials->isNotEmpty())
                                                    @foreach($labourTimesheet1->timesheetMaterials as $timesheetMaterial1)
                                                        @php 
                                                            $per_total_cost = ($per_estimate * (float)$timesheetMaterial1->hours);
                                                            $tt = (float)$timesheetMaterial1->hours;	 
                                                            $a = '';
                                                            $a = (float)$timesheetMaterial1->hours;
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
                                                            
                                                            @$spent_rate = ($timesheetMaterial1->hours*$timesheetMaterial1->rate);
                                               
                                                        @endphp
                                                        <tr>
                                                            <td>					  
                                                                {{ $timesheetMaterial1->lab_code }}
                                                            </td>
                                                            <td>{{ date("d/m/Y", strtotime($labourTimesheet1->timesheet_date)) }}						  
                                                                <!--{{ date("d/m/Y", strtotime($timesheetMaterial1->timesheet_date)) }}-->
                                                            </td>
                                                            <td>						  
                                                                {{ $timesheetMaterial1->operative }}
                                                            </td>
                                                            <td>
                                                                {{ $timesheetMaterial1->start_time }}
                                                            </td>
                                                            <td>
                                                                {{ $timesheetMaterial1->end_time }}
                                                            </td>
                                                            <td>1</td>
                                                            <td>
                                                                {{ $timesheetMaterial1->hours }}
                                                            </td> 
                                                            <td>
                                                                {{ $timesheetMaterial1->rate }}
                                                            </td>
                                                            <td>
                                                         
                                                            
                                                         
                                                                &pound;{{ number_format($spent_rate, 2) }}
                                                                <!--&pound;{{ number_format($per_total_cost, 2) }}-->
                                                            </td>
                                                            <!--<td>
                                                                &pound;{{ number_format($per_estimate, 2) }}/hr
                                                            </td>
                                                            <td></td>
                                                            <td></td>-->
                                                        </tr>
                                                        @php
                                                            $tmj = ($tmj+$pm);
                                                            @$total_hours = ($total_hours+$ph);
                                                
                                                            @$a_total_cost = ($a_total_cost+$spent_rate);
                                                          
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
                                                        $("#pt{{ $loop->iteration }}").html('{{ $total_hours }}:{{ $tmj }}');
                                                    }else{
                                                        $("#pt{{ $loop->iteration }}").html("{{ $total_hours }}:{{ $tmj }}");
                                                    } 
                                                    $("#at{{ $loop->iteration }}").html("&pound;{{ number_format($a_total_cost, 2) }}");   
                                                    $("#tp{{ $loop->iteration }}").html("&pound;{{ number_format($profit, 2) }}");
                                                    $("#tl{{ $loop->iteration }}").html("&pound;{{ number_format($loss, 2) }}");	
                                                      
                                                   
                                                </script>
                                                @php
                                                    $all_total_hours = ($all_total_hours+$total_hours);  
                                                    $all_total_cost = ($all_total_cost+$total_cost);
                                                    $total_estimate = (float)$labourTimesheet->allocated_hour*$per_estimate;
                                                    $tm = ($tm+$tmj);
                                                @endphp
                                            @endforeach
                                        @endif
                                        @php 
                                            if($tm > 60){
                                                $all_total_hours = ($all_total_hours+floor($tm/60));
                                                $all_total_hourss = ($all_total_hourss + $all_total_hours);
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
                            @php
                                $rh = $all_total_hours;
                                $ah = strtok((float)$labourTimesheet->allocated_hour, ':');
                                $tot = $ah - $rh;
                            @endphp
                          
	                        @if($rh > $ah)
							    $("#allrh{{ $loop->iteration }}").html('<font style="color: red;">{{ $labourTimesheet->remaining_hour }}</font>');
							@else
							  $("#allrh{{ $loop->iteration }}").html('{{ $labourTimesheet->remaining_hour }}');
	                        @endif	
	                        
	                        $("#allrhs{{ $loop->iteration }}").html("{{ $tot }}");
                            $("#allth{{ $loop->iteration }}").html("{{ $all_total_hours }}:{{ $tm }} ");
                            $("#alltc{{ $loop->iteration }}").html("&pound;{{ number_format($all_total_cost,2) }}");
                            $("#allte{{ $loop->iteration }}").html("&pound;{{ number_format($total_estimate,2) }}");
                            $("#alltp{{ $loop->iteration }}").html("&pound;{{ number_format($total_profit,2) }}");
                            $("#alltl{{ $loop->iteration }}").html("&pound;{{ number_format($total_loss,2) }}");
                        </script>
                    @endforeach
                    <tr>
                        <td colspan="7"></td>
                        <td class="text-right">Total Cost : &pound;{{ number_format($gall_total_cost,2) }}</td>
                        <td class="text-right">Estimate : &pound;{{ number_format($gtotal_estimate,2) }}</td>
                        <td class="text-right">Profit : &pound;{{ number_format($gtotal_profit,2) }}</td>
                        <td class="text-right">Loss : &pound;{{ number_format($gtotal_loss,2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>	
    </div>
</div>

<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#report-labour-timesheet-datatable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>