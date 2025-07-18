  
   

<div class="card-body printable-div" id="printable-div">

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
    <div class="table-responsive">
   <table class="table-responsive table table-hover table-bordered table-striped table-info" align="center">
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
				                <thead>
									<tr class="table-success">
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
									 
						<tr>
									  
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
								{{ $labourTimesheet->total_spent_hour }}
							
						    </td>
	                        <td>
	                            {{ $labourTimesheet->remaining_hour }}
							   <!--<span id="allrh{{ $loop->iteration }}">{{ $labourTimesheet->remaining_hour }}</span>-->
							  
						   </td>
                            <td>{{ number_format($all_total_cost,2) }}</td>
                            <td>{{ number_format($total_estimate,2) }}</td>
                            <td>{{ number_format($total_profit,2) }}</td>
                            <td>{{ number_format($total_loss,2) }}</td>						
                        </tr>	
                                    <tbody>
                                        @php
                                            $labourTimesheets1 = $labourTimesheet->project->labourTimesheets()
                                                    ->where('activity_id', $labourTimesheet->activity_id)
                                                    ->get();
													
													
                                        @endphp
									
                                    
                                        @if($labourTimesheets1->isNotEmpty())
                                            @foreach($labourTimesheets1 as $labourTimesheet1)
										
												
						 <tr>
                                                @php
                                                    $total_hours = 0;
                                                    $a_total_cost = 0;
                                                    $tmj = 0;
                                                    $thj = 0;
                                                    $estimate = $labourTimesheet1->activityOfTimesheet->total;	
                                                    $per_estimate = $labourTimesheet1->activityOfTimesheet->selling_cost;
                                                @endphp
												
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
                                                <tr>
                                                    <td>						  
                                                        {{ $labourTimesheet1->activityOfTimesheet->item_code }}
                                                    </td>
                                                    <td><!--{{ date("d/m/Y", strtotime($labourTimesheet1->timesheet_date)) }}--></td>
                                                    <td></td>
                                                    <td></td>
                                                   
                                                    <td>
                                                       {{ number_format($labourTimesheet1->activityOfTimesheet->quantity, 2) }}
                                                        
                                                    </td>
                                                    <td>{{ $labourTimesheet1->peoples }}</td> 
                                                    <td>
                                                       {{ $labourTimesheet1->spent_hour }}
                                                    </td>
                                                    <!-- <td></td>-->
                                                    
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
                                                         
                                                            
                                                         
                                                                {{ number_format($spent_rate, 2) }}
                                                                <!--&pound;{{ number_format($per_total_cost, 2) }}-->
                                                            </td>
                                                            <!--<td>
                                                                &pound;{{ number_format($per_estimate, 2) }}/hr
                                                            </td>
                                                            <td></td>
                                                            <td></td>-->
                                                        </tr>
														
                                                        @php
                                                         
                                                          
                                                        @endphp
                                                    @endforeach
                                                @endif
                                                @php 
                                                   
                                                @endphp
                                                <script>

                                                      
                                                   
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
                                
								
                           
                       
                   @endforeach
                    <tr>
                        <td colspan="6"></td>
                        <td class="text-right">Total Cost :{{ number_format($gall_total_cost,2) }}</td>
                        <td class="text-right">Estimate :{{ number_format($gtotal_estimate,2) }}</td>
                        <td class="text-right">Profit : {{ number_format($gtotal_profit,2) }}</td>
                        <td class="text-right">Loss : {{ number_format($gtotal_loss,2) }}</td>
                    </tr>
					
                @endif
          </table>
    </div>
</div>
