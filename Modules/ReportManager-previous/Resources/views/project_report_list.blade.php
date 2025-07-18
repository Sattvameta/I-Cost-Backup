<div class="card-header">
    <h3 class="card-title">Project Reports</h3>
</div>
<div class="card-body">
         <div class="col-md-3">
                  <div class="form-group">
					  <label>Search</label><br>
						   <input id="myInput" type="text">
                         </a>
					</div>
               </div>
    <table class="table-responsive table table-bordered project-report-datatable" id="project-report-datatable">
        <thead>
            <tr>
                <td colspan="8">
                    <input type="button" class="btn btn-primary btn-sm" value="Expand all" title="Expand all" id="expand_all">
                    <input type="button" class="btn btn-success btn-sm" value="Collaps all" title="Collaps all" id="collaps_all">
                </td>
            </tr>
            <tr class="table-success">
                <th width="10%">Sr No.</th>
                <th>Unique Ref</th>
                <th>Project Name</th>
                <th>Estimate</th>
                <th>Actuals</th>
                <th>Profit</th>
                <th>Loss</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if($projects->isNotEmpty())
                @foreach($projects as $project)
                    @php
                        $estimate = 0;
                        $purchases = 0;
                        $profit1 = 0;
                        $profit = 0;
                        $loss1 = 0;
                        $loss = 0;
                        $project_total = 0;
                        $project_hr_total = 0;
                        $project_mhr_total = 0;
                            
                        $labour_total = 0;
                        $labour_total_hour = 0;	
                        $manager_total = 0;
                        $material_cost = 0;
                        $plant_cost = 0;
                        $design_total = 0;	
                            
                        $manager_total_hour = 0;
                        $material_cost_hour = 0;
                        $plant_cost_hour = 0;	
                        $design_total_hour = 0;
                                    
                        $base_margin = $project->base_margin;
                        
                        $hr_rate = $project->hr_rate;	
                        $mhr_rate = $project->mhr_rate;
                                                            
                        $mhr_total_hours = 0;
                        $mhr_a_total_cost = 0;
                        $mhr_tmj = 0;
                        $mhr_thj = 0;

                        $dhr_total_hours = 0;
                        $dhr_a_total_cost = 0;
                        $dhr_tmj = 0;
                        $dhr_thj = 0;

                        $fq = array();
                        $fk = array();
                        $fqka = array();
                        $fqkah = array();
                        $labour_total_formula = "";

                        $total_estimate = 0;
                        $total_purchases = 0;
                        $total_profit = 0;
                        $total_loss = 0;
                    @endphp
                    @if($project->mainActivities->isNotEmpty())
                        @foreach($project->mainActivities as $mainActivity)
                            @php 
                                $estimate =  ($estimate + $mainActivity->total);
                                $main_activity_id = $mainActivity->id;
                                $project_total =  $project_total + $mainActivity->total;
                                $project_hr_total =  $project_hr_total + $mainActivity->total_hr;
                                $project_mhr_total =  $project_mhr_total + $mainActivity->total_mhr;
                            @endphp
                            @if($mainActivity->subActivities->isNotEmpty())
                                @foreach($mainActivity->subActivities as $subActivity)
                                    @if($subActivity->activities->isNotEmpty())
                                        @foreach($subActivity->activities as $activity)
                                            @php 
                                                $unit_trim = preg_replace('/\s+/', '', $activity->unit);
                                                 
                                                $formulas = $project->formulas->where('keyword', '!=', '');
                                            @endphp  
                                            @if($formulas->isNotEmpty())
                                                @foreach($formulas as $formula)
                                                
                                                @if($unit_trim == $formula->keyword)
                                                    @php 
                                                        $labour_total = $labour_total + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                        $labour_total_hour = $labour_total_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                                        $fq[$unit_trim] = @$fq[$unit_trim]+($activity->quantity * $subActivity->quantity * $mainActivity->quantity);
                                                        $fk[$unit_trim] = @$fk[$unit_trim] + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                        $labourTimesheets = $project->labourTimesheets->where('activity_id', $activity->id);
                                                    @endphp
                                                
                                                    @if($labourTimesheets->isNotEmpty())
                                                        @foreach($labourTimesheets as $labourTimesheet)
                                                            @if($labourTimesheet->timesheetMaterials->isNotEmpty())
                                                                @foreach($labourTimesheet->timesheetMaterials as $timesheetMaterial)
                                                              
                                                                    @php 
                                                                        $k_tt = (float)$timesheetMaterial->hours;	 
                                                                        $k_a = (float)$timesheetMaterial->hours;
                                                                        if (strpos($k_a, ':') !== false) {

                                                                        }else{
                                                                            $k_tt = str_pad($k_tt, 2, '0', STR_PAD_LEFT).':00';
                                                                        }	 
                                                                        $k_dtm = new DateTime('2019-11-09 '.$k_tt.'');

                                                                        $k_time = $k_dtm->format('H:i');

                                                                        $k_ph = $k_dtm->format('H');
                                                                        $k_pm = $k_dtm->format('i');

                                                                        $k_tmin = ($k_ph*60)+$k_pm;	
                                                                
                                                                        $k_rate = 0;
                                                                        $k_rate = $timesheetMaterial->rate;	 
                                                                        $k_rate_minpercost = $k_rate/60;	

                                                                        $k_per_total_cost = ($k_tmin*$k_rate_minpercost);
                                                                
                                                                        @$k_tmj = ($k_tmj+$k_pm);
                                                                        @$k_total_hours = ($k_total_hours+$k_ph);
                                                                        @$k_a_total_cost = ($k_a_total_cost+$k_per_total_cost);
                                                                    
                                                                        @$fqka[$unit_trim] = ($fqka[$unit_trim]+$k_per_total_cost);
                                                                        @$fqkah[$unit_trim] = ($fqkah[$unit_trim]+$k_ph);
                                                                    @endphp
                                                                @endforeach
                                                                @php 
                                                                    if($k_tmj > 60){
                                                                        $k_total_hours = ($k_total_hours+floor($k_tmj/60));
                                                                        $k_tmj = floor($k_tmj%60);
                                                                    }
                                                                    $k_total_cost = $k_a_total_cost;
                                                                    $k_profit = 0;
                                                                    $k_loss = 0;
                                                                    if($k_total_cost > $activity->total)
                                                                    {
                                                                        $k_loss = ($k_total_cost - $activity->total);
                                                                    }
                                                                    if($k_total_cost < $activity->total)
                                                                    {
                                                                        $k_profit = ($activity->total - $k_total_cost);
                                                                    }
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    
                                                    
                                                    
                                                    
                                                @endif    
                                                @endforeach

                                            @endif
                                            
                                            @if($unit_trim == "nrp")
                                                @php
                                                    $plant_cost = $plant_cost + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                    $plant_cost_hour = $plant_cost_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                                @endphp
                                            @endif
                                            @if($unit_trim == "nr")
                                                @php
                                                    $material_cost = $material_cost + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                    $material_cost_hour = $material_cost_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                                @endphp  
                                            @endif
                                            @if($unit_trim == "mhr")	
                                                @php 
                                                    $manager_total = $manager_total + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                    $manager_total_hour = $manager_total_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                                    $mhr_estimate = $activity->total;
                                                    $mhr_per_estimate = $activity->selling_cost;

                                                    $staffTimesheets = $project->staffTimesheets->where('role', 'detail')->where('activity_id', $activity->id);
                                                @endphp
                                                @if($staffTimesheets->isNotEmpty())
                                                    @foreach($staffTimesheets as $staffTimesheet)
                                                        @php 
                                                            $mhr_per_total_cost = ($activity->selling_cost * (float)$staffTimesheet->total_hours);	  
                                                            $tt = (float)$staffTimesheet->total_hours;	 
                                                            $a = '';
                                                            $a = (float)$staffTimesheet->total_hours;
                                                            if (strpos($a, ':') !== false) {

                                                            }else{
                                                                $tt = str_pad($tt, 2, '0', STR_PAD_LEFT).':00';
                                                            }	
                                                            $dtm = new DateTime('2019-11-09 '.$tt.'');

                                                            $time = $dtm->format('H:i');

                                                            $ph = $dtm->format('H');
                                                            $pm = $dtm->format('i');

                                                            $tmin = ($ph*60)+$pm;	

                                                            $minpercost = $mhr_per_estimate/60;	

                                                            $mhr_per_total_cost = ($tmin*$minpercost);
                                                            
                                                            $mhr_tmj = ($mhr_tmj+$pm);
                                                            @$mhr_total_hours = ($mhr_total_hours+$ph);;
                                                            @$mhr_a_total_cost = ($mhr_a_total_cost+$mhr_per_total_cost);
                                                        @endphp 
                                                    @endforeach
                                                    @php 
                                                        if($mhr_tmj>60){
                                                            $mhr_total_hours = ($mhr_total_hours+floor($mhr_tmj/60));
                                                            $mhr_tmj = floor($mhr_tmj%60);
                                                        }	 

                                                        $mhr_total_cost = $mhr_a_total_cost;
                                                        $mhr_profit = 0;
                                                        $mhr_loss = 0;
                                                        if($mhr_total_cost > $activity->total){
                                                            $mhr_loss = ($mhr_total_cost - $activity->total);
                                                        }
                                                        if($mhr_total_cost < $activity->total){
                                                            $mhr_profit = ($activity->total - $mhr_total_cost);
                                                        }
                                                    @endphp
                                                @endif
                                            @endif
                                            @if($unit_trim == "dhr")
                                                @php 
                                                    $design_total = $design_total + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                    $design_total_hour = $design_total_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                                    $dhr_estimate = $activity->total;
                                                    $dhr_per_estimate = $activity->selling_cost;

                                                    $staffTimesheets = $project->staffTimesheets->where('role', 'detail')->where('activity_id', $activity->id);
                                                @endphp
                                                @if($staffTimesheets->isNotEmpty())
                                                    @foreach($staffTimesheets as $staffTimesheet)
                                                        @php 
                                                            $dhr_per_total_cost = ($activity->selling_cost * (float)$staffTimesheet->total_hours);  
                                                            $dhr_tt = (float)$staffTimesheet->total_hours;	 
                                                            $dhr_a = '';
                                                            $dhr_a = (float)$staffTimesheet->total_hours;
                                                            if (strpos($dhr_a, ':') !== false) {

                                                            }else{
                                                                $dhr_tt=str_pad($dhr_tt, 2, '0', STR_PAD_LEFT).':00';
                                                            }	 
                                                            $dhr_dtm = new DateTime('2019-11-09 '.$dhr_tt.'');

                                                            $dhr_time = $dhr_dtm->format('H:i');

                                                            $dhr_ph = $dhr_dtm->format('H');
                                                            $dhr_pm = $dhr_dtm->format('i');

                                                            $dhr_tmin = (($dhr_ph*60)+$dhr_pm);	

                                                            $dhr_minpercost = $dhr_per_estimate/60;	

                                                            $dhr_per_total_cost = ($dhr_tmin*$dhr_minpercost);
                                                            
                                                            $dhr_tmj = ($dhr_tmj+$dhr_pm);
                                                            @$dhr_total_hours = ($dhr_total_hours+$dhr_ph);
                                                            @$dhr_a_total_cost = ($dhr_a_total_cost+$dhr_per_total_cost);
                                                        @endphp  
                                                    @endforeach
                                                    @php 
                                                        if($dhr_tmj > 60){
                                                            $dhr_total_hours = ($dhr_total_hours+floor($dhr_tmj/60));
                                                            $dhr_tmj = floor($dhr_tmj%60);
                                                        }	 

                                                        $dhr_total_cost = $dhr_a_total_cost;
                                                        $dhr_profit = 0;
                                                        $dhr_loss = 0;
                                                        if($dhr_total_cost > $activity->total){
                                                            $dhr_loss = ($dhr_total_cost - $activity->total);
                                                        }
                                                        if($dhr_total_cost < $activity->total){
                                                            $dhr_profit = ($activity->total - $dhr_total_cost);
                                                        }
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                    @if($project->purchases->isNotEmpty())
                        @foreach($project->purchases as $purchase)
                            @if($purchase->orders->isNotEmpty())
                                @foreach($purchase->orders as $order)
                                    @php 
                                        $purchases =  ($purchases + $order->total);
                                    @endphp
                                @endforeach
                            @endif
                            @php 
                                $purchases = ($purchases + $purchase->carriage_costs	+ $purchase->c_of_c	+ $purchase->other_costs);
                            @endphp
                        @endforeach
                    @endif
                    @php 
                        $profit1 = $estimate - $purchases;
                        if($profit1 > 0){
                            $profit = $profit1;
                        }
                        $loss1 = $purchases - $estimate;
                        if($loss1 > 0){
                            $loss = $loss1;
                        }
                        
                        $actual_project_total = 0;
                        $actual_labour_total_hour = 0;
                        $actual_labour_total=0;
                        
                        $actual_project_total = ($actual_project_total+$mhr_a_total_cost);
                        $actual_project_total = ($actual_project_total+$dhr_a_total_cost);
                        $actual_project_total = ($actual_project_total+$plant_cost);
                    @endphp
                    @foreach($fq as $key=>$value)	
                        @php 
                            if(isset($fqka[$key])){
                                $actual_project_total = ($actual_project_total+$fqka[$key]);
                            }
                            if(isset($fqkah[$key])){
                                $actual_labour_total_hour = $actual_labour_total_hour+$fqkah[$key];
                            }
                            if(isset($fqka[$key])){
                                $actual_labour_total = $actual_labour_total+$fqka[$key];  
                            }                           
                            $labour_total_formula .= " + ".$key;
                        @endphp
                    @endforeach
                    @php 
                        $actual_project_total = ($actual_project_total + $purchases);
                    @endphp

                    <tr row-id="{{ $project->id }}" class="expandable">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $project->unique_reference_no }}</td>
                        <td>{{ $project->project_title }}</td>
                        <td>&pound;{{ number_format($estimate, 4) }}</td>
                        <td>
                            &pound;{{ number_format($actual_project_total, 2) }}
                        </td>
                        <td>
                            @if($estimate > $actual_project_total)
                                &pound;{{ number_format(($estimate - $actual_project_total), 2) }}
                            @endif
                        </td>
                        <td>
                            @if($actual_project_total > $estimate)
                                &pound;{{ number_format(($actual_project_total - $estimate), 2) }}
                            @endif
                        </td>
                        <td>
                            <a href="" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                            <input class="btn btn-success btn-sm expandable-input" title='Click here to expand this' type="button" value="+">   
                        </td>
                    </tr>
                    <tr row-id="{{ $project->id }}">
                        <td colspan="8" class="text-center expandable">
                            <div class="col-md-12" style="display:none">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Project Total</th>
                                            <th colspan="2">Labour Total<br><small>{{ $labour_total_formula }}</small></th>
                                            <th colspan="2">
                                                Manager Total<br><small>mhr</small>
                                            </th>
                                            <th colspan="2">Design Total<br><small>dhr</small></th>
                                            <th>Material Cost<br><small>nr</small></th>
                                            <th>Plant Cost<br><small>nrp</small></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="project_total" id="project_total" readonly="readonly" value="{{ $project_total }}">&pound;{{ number_format((float)$project_total,2) }}
                                            </td>
                                            <td>{{ number_format((float)$labour_total_hour).'hrs' }}</td>
                                            <td>
                                                <input type="hidden" name="project_hr_total" id="project_hr_total" readonly="readonly" value="{{ $project_hr_total }}">&pound;{{ number_format((float)$labour_total,2) }}
                                            </td>
                                            <td>{{ number_format((float)$manager_total_hour).'hrs' }}</td>
                                            <td>
                                                <input type="hidden" name="project_mhr_total" id="project_mhr_total" readonly="readonly" value="{{ $project_mhr_total }}"> &pound;{{ number_format((float)$manager_total,2) }} actual: {{ $mhr_total_hours }} hrs, {{ $mhr_a_total_cost }}
                                            </td>
                                            <td>{{ number_format((float)$design_total_hour).'hrs' }}</td>
                                            <td>
                                                <input type="hidden" name="project_dhr_total" id="project_dhr_total" readonly="readonly" value="{{ @$project_dhr_total }}">
                                                &pound;{{ number_format((float)$design_total, 2) }} actual: {{ $dhr_total_hours }} hrs, {{ $dhr_a_total_cost }}
                                            </td>
                                            <td>
                                                <input type="hidden" name="project_mhr_total" id="project_mhr_total" readonly="readonly" value="{{ $project_total-(($project_mhr_total)+($project_hr_total)) }}">
                                                &pound;{{ number_format((float)$material_cost, 2) }}
                                            </td>
                                            <td>
                                                <input type="hidden" name="plant_total" id="plant_total" readonly="readonly" value="{{ $project_total-(($project_mhr_total)+($project_hr_total)) }}">
                                                &pound;{{ number_format((float)$plant_cost, 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Base Margin</th>
                                            <th>Base Labour</th>
                                            @foreach($fq as $key=>$value)
                                                <th colspan="2">{{ $key }}</th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>{{ $base_margin }}</th>
                                            <th>{{ $hr_rate }}</th>
                                            @foreach($fq as $key=>$value)
                                                <th>{{ $value }} hrs</th>
                                                <th> &pound;{{ @$fk[$key] }}actual:{{ @$fqkah[$key] }}hrs,&pound;{{ @$fqka[$key] }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>		
                            <div class="col-md-12">
                                <table class="table-responsive table table-striped table-no-bordered table-hover">
                                    <thead style="background-color:lightgrey;">    
                                        <tr>
                                        <th>Category</th>
                                        <th>Estimate</th>
                                        <th>Actual</th>
                                        <th>Profit</th>
                                        <th>Loss</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Project Total</td>	 
                                            <td>&pound;{{ number_format($estimate, 2) }}</td>
                                            <td>&pound;{{ number_format($actual_project_total, 2) }}</td>
                                            <td>
                                                @if($estimate>$actual_project_total)
                                                    &pound;{{ number_format(($estimate-$actual_project_total), 2) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($actual_project_total > $estimate)
                                                    &pound;{{ number_format(($actual_project_total-$estimate),2) }}
                                                @endif
                                            </td>		  
                                        </tr>		  
                                        <tr>
                                            <td>Material Cost<small>(nr)</small></td>
                                            <td>&pound;{{ number_format((float)$material_cost, 2) }}</td>
                                            <td>&pound;{{ number_format((float)$purchases, 2) }}</td> 
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Plant Cost<small>(nrp)</small></td>
                                            <td>&pound;{{ number_format((float)$plant_cost ,2) }}</td>
                                            <td>
                                                &pound;{{ number_format((float) 0, 2) }}
                                            </td> 
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Manager Total<small>(mhr)</small></td>
                                            <td>
                                                <span class="float-left">
                                                    {{ number_format((float)$manager_total_hour) }}hrs
                                                </span>
                                                <span class="float-right">
                                                    &pound;{{ number_format((float)$manager_total, 2) }}
                                                </span>	  
                                            </td>
                                            <td>
                                                <span class="float-left">
                                                    {{ number_format((float)$mhr_total_hours) }}hrs
                                                </span>
                                                <span class="float-right">
                                                    {{ number_format((float)$mhr_a_total_cost,2) }}
                                                </span>	  
                                            </td> 
                                            <td>
                                                @if($manager_total > $mhr_a_total_cost)
                                                    <span class="float-left">
                                                        {{ number_format((float)($manager_total_hour-$mhr_total_hours)) }}hrs
                                                    </span>
                                                    <span class="float-right">
                                                        &pound;{{ number_format((float)($manager_total-$mhr_a_total_cost), 2) }}
                                                    </span>	
                                                @endif
                                            </td>
                                            <td>
                                                @if($mhr_a_total_cost>$manager_total)
                                                    <span class="float-left">
                                                        {{ number_format((float)($mhr_total_hours-$manager_total_hour)) }}hrs
                                                    </span>
                                                    <span class="float-right">
                                                        &pound;{{ number_format((float)($mhr_a_total_cost-$manager_total), 2) }}
                                                    </span>
                                                @endif
                                            </td>  
                                        </tr> 
                                        <tr>
                                            <td>Design Total<small>(dhr)</small></td>
                                            <td>
                                                <span class="float-left">
                                                    {{ number_format((float)$design_total_hour) }}hrs
                                                </span>
                                                <span class="float-right">
                                                    &pound;{{ number_format((float)$design_total, 2) }}
                                                </span>	  
                                            </td>
                                            <td>
                                                <span class="float-left">
                                                    {{ number_format((float)$dhr_total_hours) }}hrs
                                                </span>
                                                <span class="float-right">
                                                    &pound;{{ number_format((float)$dhr_a_total_cost, 2) }}
                                                </span>	  
                                            </td> 
                                            <td>
                                                @if($design_total>$dhr_a_total_cost)
                                                    <span class="float-left">
                                                        {{ number_format((float)($design_total_hour-$dhr_total_hours)) }}hrs
                                                    </span>
                                                    <span class="float-right">
                                                        &pound;{{ number_format((float)($design_total-$dhr_a_total_cost), 2) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($dhr_a_total_cost>$design_total)
                                                    <span class="float-left">
                                                        {{ number_format((float)($dhr_total_hours-$design_total_hour)) }}hrs
                                                    </span>
                                                    <span class="float-right">
                                                        &pound;{{ number_format((float)($dhr_a_total_cost-$design_total), 2) }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="table-active">
                                            <td>Labour Total<small><br>({{ $labour_total_formula }})</small></td>
                                            <td>
                                                <span class="float-left">
                                                    {{ number_format((float)$labour_total_hour) }}hrs
                                                </span>
                                                <span class="float-right">
                                                    &pound;{{ number_format((float)$labour_total, 2) }}
                                                </span>	  
                                            </td>
                                            <td>
                                                <span class="float-left">
                                                    {{ number_format((float)$actual_labour_total_hour) }}hrs
                                                </span>
                                                <span class="float-right">
                                                    &pound;{{ number_format((float)$actual_labour_total,2) }}
                                                </span>	  
                                            </td> 
                                            <td>
                                                @if($labour_total > $actual_labour_total)
                                                    <span class="float-left">
                                                        {{ number_format((float)($labour_total_hour-$actual_labour_total_hour)) }}hrs
                                                    </span>
                                                    <span class="float-right">
                                                        &pound;{{ number_format((float)($labour_total-$actual_labour_total), 2) }}
                                                    </span>  
                                                @endif
                                            </td>
                                            <td>
                                                @if($actual_labour_total > $labour_total)
                                                    <span class="float-left">
                                                        {{ number_format((float)($actual_labour_total_hour-$labour_total_hour)) }}hrs
                                                    </span>
                                                    <span class="float-right">
                                                        &pound;{{ number_format((float)($actual_labour_total-$labour_total), 2) }}
                                                    </span>  
                                                @endif
                                            </td>
                                        </tr> 
                                    </tbody>	
                                </table>        
                            </div>        
                            @php 
                                $fq = array();
                                $fk = array();
                                $fqka = array();
                                $fqkah = array();
                                $labour_total_formula = "";
                            @endphp			  
                        </td>				  
                    </tr>
                    <tfoot>
                        <tr style="display: none;">
                            <td></td>
                            <td></td>
                            <td>Total</td>
                            <td>&pound;{{ number_format($total_estimate, 4) }}</td>
                            <td>&pound;{{ number_format($total_purchases, 4) }}</td>
                            <td>@if($total_profit > 0) &pound; {{ number_format($total_profit, 4) }} @endif</td>
                            <td>@if($total_loss > 0)&pound; {{ number_format($total_loss,4) }} @endif</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @php 
                        $total_estimate = ($total_estimate + $estimate);
                        $total_purchases = ($total_purchases + $purchases);
                        $total_profit = ($total_profit + $profit);
                        $total_loss = ($total_loss + $loss);
                    @endphp
                @endforeach
            @endif
        </tbody>
    </table>
</div>
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#project-report-datatable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>