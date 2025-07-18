@extends('layouts.app')
@section('title','Dashboard')
@section('content')





                           <div class="app-main__inner">
                               
                               
                               @include('layouts.flash.alert')<!-- Alerts -->
                               
                               
                               
                               
                               
                               
                               
                               
                               
                               
     <div class="card-body">
    <table class="table table-bordered project-report-datatable" id="project-report-datatable">
        
        <tbody>
            @if($projects->isNotEmpty())
                @foreach($graphs['costperformance'] as $k=>$project)
              
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
                                                                    @$dhr_total_hours = ($dhr_total_hours+$ph);;
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
                                                    @if($unit_trim == "nr")
                                                        @php
                                                            $material_cost = $material_cost + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                            $material_cost_hour = $material_cost_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                                        @endphp
                                                    @endif
                                                    @if($unit_trim == "nrp")
                                                        @php
                                                            $plant_cost = $plant_cost + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                            $plant_cost_hour = $plant_cost_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                                        @endphp
                                                    @endif
                                                @endforeach

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

                    <!--<tr row-id="{{ $project->id }}" class="expandable">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $project->unique_reference_no }}</td>
                        <td>{{ $project->project_title }}</td>
                       
                        <td>&pound;{{ number_format($estimate, 4) }}</td>
                        <td>
                            &pound;{{ number_format($actual_project_total, 2) }}
                        </td>-->
                     
                        <?php $graphs['costperformance'][$k]['actualss'] = $actual_project_total; ?>
                        <!--<td>
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
                    </tr>-->
               
               
                
                @endforeach
            @endif
         </tbody>
    </table>
</div>
                               
                               
                               
                               
                            <?php
/*$sumArray = array();
$cost_performance_graph = array();
foreach ($graphs['costperformance'] as $k=>$subArray) {

    $sumArray['project_total'][$k] = $subArray['project_total'];
    $sumArray['actualss'][$k] = $subArray['actualss']; 

}*/

?>
<div class="card-body">
    <table class="table table-bordered project-report-datatable" id="project-report-datatable">
        
        <tbody>
            @if($projects->isNotEmpty())
                @foreach($graphs['costperformance'] as $k=>$project)
              
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
                                                                    @$dhr_total_hours = ($dhr_total_hours+$ph);;
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
                                                    @if($unit_trim == "nr")
                                                        @php
                                                            $material_cost = $material_cost + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                            $material_cost_hour = $material_cost_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                                        @endphp
                                                    @endif
                                                    @if($unit_trim == "nrp")
                                                        @php
                                                            $plant_cost = $plant_cost + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                            $plant_cost_hour = $plant_cost_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                                        @endphp
                                                    @endif
                                                @endforeach

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

                    <!--<tr row-id="{{ $project->id }}" class="expandable">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $project->unique_reference_no }}</td>
                        <td>{{ $project->project_title }}</td>
                       
                        <td>&pound;{{ number_format($estimate, 4) }}</td>
                        <td>
                            &pound;{{ number_format($actual_project_total, 2) }}
                        </td>-->
                     
                        <?php $graphs['costperformance'][$k]['actualss'] = $actual_project_total; ?>
                        <!--<td>
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
                    </tr>-->
               
               
                
                @endforeach
            @endif
        </tbody>
    </table>
</div>    
    

      
<?php
$sumArray = array();
$live_projects = 0;
$cost_performance_graph = array();


if(count($graphs['costperformance'])){
    foreach ($graphs['costperformance'] as $k=>$subArray) {
       
        if($subArray['tender_status'] == 1){
           $live_projects =  $live_projects +1;
        }
        $sumArray['project_total'][$k] = $subArray['project_total'];
        $sumArray['actualss'][$k] = $subArray['actualss']; 
    
    }
}

//print_r(count($sumArray));exit;
?>

                               <!--<div class="tabs-animation" style="max-width:1000px" >
                            <div class="row">
                                <div class="col-md-6 col-xl-3"> <a style="text-decoration: none;" href="{{ route('projects.add')}}" >
                                    <div class="card mb-3 widget-content bg-night-fade" style="background-image: linear-gradient(to top, #aa3963 0%, #fbc2eb 100%) !important;border-radius: 74px;">
                                        <div class="widget-content-wrapper text-white">
                                            <div class="widget-content-left">
                                                <div class="widget-heading">Create Project</div>
                                                <div class="widget-subheading"></div>
                                            </div>
                                        </div>
                                    </div></a>
                                </div>
                                                                
                            </div>
                            
                               </div>-->
                               
                               
                             @if(auth()->user()->can('access', 'dashboard visible'))   
                      
                               <div class="tabs-animation" style="max-width:1000px" >
                            <div class="row">
                                <div class="col-md-6 col-xl-3"> <a style="text-decoration: none;" href="{{ route('projects')}}" >
                                    <div class="card mb-3 widget-content bg-night-fade bg-down-shadow">
                                        <div class="widget-content-wrapper text-black">
                                            <div class="widget-content-left">
                                                <div class="widget-heading" style="color:#2a2b2b;">New Orders</div>
                                                <div class="widget-subheading"></div>
                                            </div>
                                            <div class="widget-content-right">
                                                <div class="widget-numbers text-white">
                                                    <span style="color:#2a2b2b;">{{count($graphs['costperformance'])}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div></a>
                                </div>
                                <div class="col-md-6 col-xl-3"><a style="text-decoration: none;" href="{{ route('projects')}}" >
                                    <div class="card mb-3 widget-content bg-night-fade bg-down-shadow">
                                        <div class="widget-content-wrapper text-white">
                                            <div class="widget-content-left">
                                                <div class="widget-heading">Bounce Rate</div>
                                                <div class="widget-subheading"></div>
                                            </div>
                                            <div class="widget-content-right">
                                                <div class="widget-numbers text-white">
                                                    <span>{{$live_projects}}/{{count($graphs['costperformance'])}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div></a>
                                </div>
                                @endif
                                   @if (auth()->user()->can('access', 'users visible'))
                                <div class="col-md-6 col-xl-3"><a style="text-decoration: none;" href="{{ route('users.index')}}" >
                                    <div class="card mb-3 widget-content bg-night-fade bg-down-shadow">
                                        <div class="widget-content-wrapper text-white">
                                            <div class="widget-content-left">
                                                <div class="widget-heading">User Registrations</div>
                                                <div class="widget-subheading"></div>
                                            </div>
                                            <div class="widget-content-right">
                                                <div class="widget-numbers text-white">
                                                    <span>{{$user_count}}</span>
                                                </div>
                                            </div>
                                        </div>
                                </div></a>
                                </div>
                                   
                                   @endif
                              @if(auth()->user()->can('access', 'suppliers visible'))
                                
                                <div class="col-md-6 col-xl-3"><a style="text-decoration: none;" href="{{ route('suppliers.index')}}" >
                                    <div class="card mb-3 widget-content bg-night-fade bg-down-shadow">
                                        <div class="widget-content-wrapper text-white">
                                            <div class="widget-content-left">
                                                <div class="widget-heading">Suppliers</div>
                                                <div class="widget-subheading"></div>
                                            </div>
                                            <div class="widget-content-right">
                                                <div class="widget-numbers text-white">
                                                    <span>{{$supplier_count}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div></a>
                                </div>
                                @endif                                
                            </div>
                        </div>
                      
                               
                            
      <!--  <div class="row">
		<div class="col-md-6" style="
    max-width: 22% !important;
">
                    <div class="card">
                        <div class="card-header">
	                      <h4 class="card-title">Carbon Reduction</h4>
                        </div>
                        
                            <img src="../public/storage/settings/carbon.PNG" style="width: 426px;">
                       
                        <div class="card-footer">
                            <!--<h6>Legend</h6>
                            <i class="fa fa-circle text-info"></i> Estimate
                            <i class="fa fa-circle text-danger"></i> Purchases-->
                        <!--</div>
                    </div>
                </div><br>
				 <div class="col-md-6" style="
    max-width: 22% !important;
">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Total Project Costs
                                <small> </small>
                            </h4>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <img src="../public/storage/settings/carbon-reduce.PNG" style="width:305px;height:200px; !important">
                        </div>
                        <div class="card-footer">
                            <!--<h6>Legend</h6>
                            <i class="fa fa-circle text-info"></i> Estimate
                            <i class="fa fa-circle text-danger"></i> Purchases-->
                        <!--</div>
                    </div>
                </div><br>-->
                 
				<div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Carbon Distribution	
                               <small> </small>
                            </h4>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <div class="card-body ct-chart" id="piechart_carbon" style="height: 440px;"></div>
                        </div>
                        <div class="card-footer">
                           <h6>Legend</h6>
                            <i class="fa fa-circle text-info"></i> Estimate
                            <i class="fa fa-circle text-danger"></i> Purchases
                       </div>
                    </div>
                </div><br>
				<!-- <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="">
                    <label>Daily Status</label>
                </div>
            </div>
        </div>-->
       <!-- <div class="card-body">
            <div class="row">
                <!--//donut-->
                <!--<div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <small> </small>
                            </h4>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <div class="card-body ct-chart" id="curve_carbon" style="height:360px;"></div>
                        </div>
                        <div class="card-footer">
                            <!--<h6>Legend</h6>
                            <i class="fa fa-circle text-info"></i> Estimate
                            <i class="fa fa-circle text-danger"></i> Purchases-->
                        <!--</div>
                    </div>
                    
                </div><br>-->
                
             @if(auth()->user()->can('access', 'dashboard cost'))     
                <div class="col-md-12" style="margin-top:53px;"> 
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Cost Performance
                                <small> </small>
                            </h4>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <div class="card-body ct-chart" id="cost_performance" style="height:320px;"></div>
                        </div>
                        <div class="card-footer">
                            <!--<h6>Legend</h6>
                            <i class="fa fa-circle text-info"></i> Estimate
                            <i class="fa fa-circle text-danger"></i> Purchases-->
                        </div>
                    </div>
                </div>
                 @endif <br><br>
                @if(auth()->user()->can('access', 'total cumulative pc'))     
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Total  Cumulative Projects Costs
                                <small> </small>
                            </h4>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <div class="card-body ct-chart" id="budgetChart" style="height:340px;"></div>
                        </div>
                        <div class="card-footer">
                            <!--<h6>Legend</h6>
                            <i class="fa fa-circle text-info"></i> Estimate
                            <i class="fa fa-circle text-danger"></i> Purchases-->
                        </div>
                    </div>
                </div>
                
                @endif 
            </div>

    <!-- @if(auth()->user()->can('access', 'Profit Chart'))  
     <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="offset-md-12 col-md-12">
                    <div class="form-group">
                        {{ Form::select('project', $projects, null, [
                            'class' => "form-control multiselect-dropdown project_filter",
                            'id' => "project_filter",
                            'data-live-search'=>'true',
                            'onchange'=>'getStatics(this.value); getGantt_data(this.value); getsupplierestimate_data(this.value); getprojecttasktime(this.value); getuserhourthisweek(this.value);getpo_report(this.value);'
                            
                        ]) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body project-statics-wrapper"></div>
    </div>
   @endif 
                               
                               
    @if ((auth()->user()->can('access', 'timesheets visible')))
     <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="">
                    <label>Timesheet Management</label>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <!--//donut-->
               <!-- <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Timesheet per project
                                <small> </small>
                            </h4>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <div class="card-body ct-chart" id="curve_chart" style="height:360px;"></div>
                        </div>
                        <div class="card-footer">
                            <!--<h6>Legend</h6>
                            <i class="fa fa-circle text-info"></i> Estimate
                            <i class="fa fa-circle text-danger"></i> Purchases-->
                        <!--</div>
                    </div>
                    
                </div>
                <br><br>-->
              <!-- <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Average handle time for Project Task <!--TIMESHEETS YEARLY TASK HOUR(DONE)-->
                               <!-- <small> </small>
                            </h4>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <div class="card-body ct-chart" id="barchart_values1" style="height:380px;"></div>
                        </div>
                        <div class="card-footer">
                            <!--<h6>Legend</h6>
                            <i class="fa fa-circle text-info"></i> Estimate
                            <i class="fa fa-circle text-danger"></i> Purchases-->
                        <!--</div>
                    </div>
                </div>
               
                

            </div>
          <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Actual hours vs Budgeted Hours</h4>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <div class="card-body ct-chart" id="just_design"></div>
                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                    
                </div>
            </div>
            
          
        </div>
        
        <!--<div class="row">
            <div class="col-md-12" id ="div2" ></div> 
        </div>-->
        <!--<div class="row">
            <div class="reports-wrapper">
            </div>
        </div>
    </div> 
    @endif
    
            @if ((auth()->user()->can('access', 'timesheets visible')) || (auth()->user()->can('access', 'suppliers visible')))
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="">
                    <label><!--Timesheet Management--></label>
                <!--</div>
            </div>
        </div>
        <div class="card-body">
            
            
            
         
        
            
            <div class="row">
                @if ((auth()->user()->can('access', 'timesheets visible')))
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">PROJECT BASED REPORT
                                <small> </small>
                            </h4>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <div class="card-body ct-chart" id="piechart_3d" style="height: 440px;"></div>
                        </div>
                        <div class="card-footer">
                            <!--<h6>Legend</h6>
                            <i class="fa fa-circle text-info"></i> Estimate
                            <i class="fa fa-circle text-danger"></i> Purchases-->
                        <!--</div>
                    </div>
                </div><br>
                @endif
                @if ((auth()->user()->can('access', 'suppliers visible')))
                
                <br>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Suppliers League
                                <small> </small>
                            </h4>
                        </div>
                        <div class="card-body ct-chart" id="supplier_charts" style=" height: 440px;"></div>
                        <div class="card-footer">
                            <!--<h6>Legend</h6>
                            <i class="fa fa-circle text-info"></i> Estimate
                            <i class="fa fa-circle text-danger"></i> Purchases-->
                        <!--</div>
                    </div>
                    
                </div>
                @endif
                <!--<div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">TIMESHEET USER HOUR THIS WEEK(DONE)
                                <small> </small>
                            </h4>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <div class="card-body ct-chart" id="barchart_values"></div>
                        </div>
                        <div class="card-footer">
                           
                        </div>
                    </div>
                    
                </div>-->
          <!--  </div>
        </div>
    </div>-->   
    @endif
    
    
    
    <?php


$color = ['#00bfff', '#dc7877','#9cbb73','#9ee2d9','#9f9ee2','#e29eba'];
$country = array();
$people = array();
$who = array();

$task= array('Task 1','Task 2','Task 3','Task 4','Task 5','Task 6');
$task_tim= array(05,22,15,30,40,20);
$task_color= array('staff','staff','labour','labour','labour','staff');



$country= array('User 1','User 2','User 3','User 4','User 5','User 6');
$people= array(20,25,15,30,60,20);
$who= array('staff','labour','staff','staff','labour','staff');
$resultCount = 6;

?>
    
    
    <html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

 


    <script type="text/javascript" src="{{asset('/js/chart_loader.js')}}"></script>
    <!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
    <script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
      
    google.charts.setOnLoadCallback(drawPieChart);
      
    function drawPieChart() {

      var data = new google.visualization.arrayToDataTable([
        ["Country","People"],
        <?php
        for($i=0;$i<$resultCount;$i++){
          ?>[<?php echo "'".$country[$i]."', ".$people[$i] ?>],
        <?php } 
        ?>
        ]);

      var options = {
          title: "Percentage of Population",
          width: '100%',
          height: '200px',
          colors: [
            <?php
            for($i=0;$i<$resultCount;$i++) {
              echo "'".$color[$i]."',";
            } 
            ?>
          ]
        };
      var chart = new google.visualization.PieChart(document.getElementById('pie-chart'));
      chart.draw(data, options);
    }




    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawBarBasic);
function drawBarBasic() {

      var data = new google.visualization.arrayToDataTable([
         ['Country', 'Population', { role: 'style' }, { role: 'annotation' }],
        <?php
        for($i=0;$i<$resultCount;$i++){
          ?>[<?php echo "'".$country[$i]."', ".$people[$i].", '".$color[$i]."' , "."'".$people[$i]."'" ?>],
        <?php } 
        ?>
        ]);

      var options = {
    	    title: "",
        chartArea: {width: '100%', height : '80%'},
        hAxis: {
          title: 'd',
          minValue: 0
        },
        vAxis: {
          title: 'City'
        },
        legend: { position: "none" }
      };

      var chart = new google.visualization.BarChart(document.getElementById('bar-chart'));

      chart.draw(data, options);
    }
    

  google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawColumnChart);
    function drawColumnChart() {
      var data = google.visualization.arrayToDataTable([
        ['Country', 'Population', { role: 'style' }, { role: 'annotation' }],
        <?php
        for($i=0;$i<$resultCount;$i++){
          ?>[<?php echo "'".$country[$i]."', ".$people[$i].", '".$color[$i]."' , "."'".$people[$i]."'" ?>],
        <?php } 
        ?>
        ]);


      var options = {
        title: "Number of People per Country",
        chartArea: {width: '100%'},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("column-chart"));
      chart.draw(data, options);
  }
  </script>
  
  <style>

    body{
       
    }
    #chart_container{
        position: relative;
        padding-bottom: 684px;
        height: 0 ;
    }
    
    .chart-div{
        margin-bottom: 20px;
    }
    
    .ct-bar {

      stroke-width: 30;
    }
    
    .ct-series-a .ct-bar {
        
            stroke: #1757e3;

    }
    .ct-series-b .ct-bar {
        
            stroke: #17e3d5;

    }
</style>

  </head>
  
  <body>
    <div id="chart_container">
      <!--<div id="pie-chart" class="chart-div"></div>-->

      <!--<div id="bar-chart" class="chart-div"></div>-->

      <!--<div id="column-chart" class="chart-div"></div>-->
      
      
      
 
    </div>
  </body>
</html>
    
    
    
    <!-- /.new hours per user -->
 <script type="text/javascript">
  
  //pie chart
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
      
    
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Task'); 
        data.addColumn('number', 'Hours'); 
        data.addRows([
        <?php
        for($i=0;$i<count($timesheets['yearly_project_timesheet']);$i++){
        ?> 
        [ '<?php echo $timesheets['yearly_project_timesheet'][$i]['task']; ?>',  <?php echo $timesheets['yearly_project_timesheet'][$i]['total']; ?>],
        <?php 
        } 
        ?> 
        ]);
        
        var options = {
            chartArea:{left:20,top:30,bottom:10},
            height: 400 ,
            width: 600 ,
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_carbon'));
        chart.draw(data, options);
      }
    
  
  //pie chart
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart_pie);
      function drawChart_pie() {
      
    
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Task'); 
        data.addColumn('number', 'Hours'); 
        data.addRows([
        <?php
        for($i=0;$i<count($timesheets['yearly_project_timesheet']);$i++){
        ?> 
        [ '<?php echo $timesheets['yearly_project_timesheet'][$i]['task']; ?>',  <?php echo $timesheets['yearly_project_timesheet'][$i]['total']; ?>],
        <?php 
        } 
        ?> 
        ]);
        
        var options = {
            chartArea:{left:20,top:30,bottom:10},
            height: 400 ,
            width: 600 ,
          title: 'Hours Spent Per Project',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
      
    
    
      
      
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart_stacked);
    function drawChart_stacked() {
      /*var data = google.visualization.arrayToDataTable([
        ["Element", "Density", { role: "style" } ],
        ["Copper", 8.94, "#b87333"],
        ["Silver", 10.49, "silver"],
        ["Gold", 19.30, "gold"],
        ["Platinum", 21.45, "color: #e5e4e2"]
      ]);*/
      
   var data = google.visualization.arrayToDataTable([
        ['Genre', 't 1', 't 2', { role: 'annotation' } ],
        ['2010', 10, 24, ''],
        ['2020', 16, 22, ''],
        ['2030', 28, 19, '']
      ]);

      var options = {
       
        legend: { position: 'top', maxLines: 3 },
        bar: { groupWidth: '75%' },
        isStacked: true
      };

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      
      var chart = new google.visualization.BarChart(document.getElementById("stacked_chart"));
      chart.draw(view, options);
  }
  
   //HOURS THIS WEEK
    google.charts.load('current', {'packages':['line']});
    google.charts.setOnLoadCallback(drawChart_line);

    function drawChart_line() {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Last 7 Days');
      data.addColumn('number', 'Hours');
      
        
        
    
      data.addRows([
          
         <?php
        for($i=0;$i<count($past_week);$i++){
        
        ?> ['<?php  echo $past_week[$i]['dates'];  ?>',  <?php echo $past_week[$i]['hours']; ?>],
        
        <?php 
        } 
        ?> 
        
      
      ]);

      var options = {
        chart: {
            chartArea:{left:40,top:20},
          title: '',
          subtitle: ''
        },
        height: 340,
        width: 800,
      
      };

      var chart = new google.charts.Line(document.getElementById('curve_chart'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }
  
  

  google.charts.load('current', {'packages':['line']});
    google.charts.setOnLoadCallback(drawChart_carbon);

    function drawChart_carbon() {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Last 7 Days');
      data.addColumn('number', '');
      
        
        
    
      data.addRows([
          
         <?php
        for($i=0;$i<count($past_week);$i++){
        
        ?> ['<?php  echo $past_week[$i]['dates'];  ?>',  <?php echo $past_week[$i]['hours']; ?>],
        
        <?php 
        } 
        ?> 
        
      
      ]);

      var options = {
        chart: {
            chartArea:{left:40,top:20},
          title: '',
          subtitle: ''
        },
        height: 340,
        width: 800,
      
      };

      var chart = new google.charts.Line(document.getElementById('curve_carbon'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }
      

      
/*        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawVisualization);
        
        function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
        ['Project', 'Actuals', 'Budget', 'Average'],
        ['Project 1', 165, 938, 773],
        ['Project 2', 135, 1120, 599],
        ['Project 3', 400, 600, 200],
        ['Project 4', 700, 600, -100],
        ['Project 5', 800, 800, 0]
        ]);
        
        var options = {
        title : '',
        vAxis: {title: ''},
        hAxis: {title: 'Project'},
        seriesType: 'bars',
        height: 400,
        width: 1000,
        
        series: {2: {type: 'line'}}
        };
        
        var chart = new google.visualization.ComboChart(document.getElementById('estimate_purchase'));
        chart.draw(data, options);
        }
      */
    
        
    



  </script>
    
                            
                               </div>
@stop



@push('styles')
 <link type="text/css" rel="stylesheet" href="{{ asset('plugins/chartist-js/chartist.min.css') }}">
@endpush
@push('scripts')


  <script src="{{ asset('plugins/chartist-js/chartist.min.js') }}"></script>
  <script>
      $(document).ready(function(){

        var selectedProjectId = $(document).find('.project_filter').val();
        if(selectedProjectId){
          var projectId = selectedProjectId;
          getStatics(projectId);
          getGantt_data(projectId);
          getsupplierestimate_data(projectId);
          getprojecttasktime(projectId);
          getuserhourthisweek(projectId);
          getpo_report(projectId);
          getStaffTimesheetReportInfo(projectId);
        }
      });
      
 
    
  function getpo_report(projectId){
    var route = "{{ route('dashboard.purchase.report.list') }}";
    var route = route+"?project_id="+projectId;
    $.get(route, function(data){
       //alert(JSON.stringify(data.html));
       $('#div2').html(data.html);

        
    });
      
  }
      
      function getuserhourthisweek(projectId){
            var route = "{{ route('dashboard.getuserhourthisweek') }}";
            var route = route+"?project_id="+projectId;
            $.get(route, function(data3){
                
            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
            
            
            var data = new google.visualization.DataTable();
            data.addColumn({type: 'string', label: 'User'});
            data.addColumn({type: 'number', label: 'Total Hours'});
            data.addColumn({type: 'string', role: 'style'});
            data.addColumn({type:'string', role:'annotation'});
              
            $.each(data3, function (index, item) {
                var iNum = parseInt(item.sum);
               
               
               if(item.type == 'Labour'){
                    data.addRow([item.user,iNum,'#dc7877',item.sum]);
                }
                
                if(item.type == 'Staff'){
                    data.addRow([item.user,iNum,'#9cbb73',item.sum]);
                   
                }
                
            });
            
             
              /*var data = new google.visualization.arrayToDataTable([
                 ['User', 'Total Hours', { role: 'style' }, { role: 'annotation' }],
         
                    ['dsad',65,null,'65'] 
                
            
                ]);*/
              
              

        
              var view = new google.visualization.DataView(data);
              view.setColumns([0, 1,
                               { calc: "stringify",
                                 sourceColumn: 1,
                                 type: "string",
                                 role: "annotation" },
                               2]);
        
              var options = {
                chartArea:{left:100,top:20},
                title: "",
                
                width: 500 ,
                height: 400 ,
                bar: {groupWidth: "85%"},
                legend: { position: "none" },
              };
              
              
              var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
              chart.draw(view, options);
          }
          
            });
        }
       function getprojecttasktime(projectId){
           
            var route = "{{ route('dashboard.projecttasktime') }}";
            var route = route+"?project_id="+projectId;
            $.get(route, function(datass){
              
           
            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChart_task);
            function drawChart_task() {

            var data = new google.visualization.DataTable();
            data.addColumn({type: 'string', label: 'User'});
            data.addColumn({type: 'number', label: 'Total Hours'});
            data.addColumn({type: 'string', role: 'style'});
            data.addColumn({type:'string', role:'annotation'});
              
            $.each(datass, function (index, item) {
                var iNum = parseInt(item.sum); //Output will be 23.
                if(item.type == 'Labour'){
                    data.addRow([item.task,iNum,'#dc7877',item.task]);
                }
                
                if(item.type == 'Staff'){
                    data.addRow([item.task,iNum,'#9cbb73',item.task]);
                }
               
            });

  
              /*var data = new google.visualization.arrayToDataTable([
         ['User', 'Total Hours', { role: 'style' }, { role: 'annotation' }],
         ['df',45,'#ff0000','ff'],
         ['df',45,'#ff3399','ff']
         
           ]);*/
         
         
        
              var view = new google.visualization.DataView(data);
              view.setColumns([0, 1,
                               { calc: "stringify",
                                 sourceColumn: 1,
                                 type: "string",
                                 role: "annotation" },
                               2]);
        
              var options = {
                chartArea:{left:100,top:20},
                title: "",
                height: 400 ,
                width: 500 ,
                bar: {groupWidth: "85%"},
                legend: { position: "none" },
              };
              var chart = new google.visualization.BarChart(document.getElementById("barchart_values1"));
              chart.draw(view, options);
          }
          
            });
       }
        function getsupplierestimate_data(projectId){
            var route = "{{ route('dashboard.suppliervsestimate') }}";
            var route = route+"?project_id="+projectId;
            $.get(route, function(datas){
            
       
    
        
            google.charts.load('current', {packages: ['corechart', 'bar']});
            google.charts.setOnLoadCallback(drawBasics);
        
            function drawBasics() {
                
                
                
              
              
              
            var data = new google.visualization.DataTable();
            data.addColumn({type: 'string', label: 'Supplier'});
            data.addColumn({type: 'number', label: 'Amount'});
              
            $.each(datas, function (index, item) {
                data.addRow([String(index), Number(item)]);
             });
                
                
        
              var options = {
                title: '',
                chartArea: {width: '50%'},
                hAxis: {
                  title: ''
                
                },
                 height: 400,
                vAxis: {
                  title: ''
                }
              };
        
              var chart = new google.visualization.BarChart(document.getElementById('supplier_charts'));
        
              chart.draw(data, options);
            }
            
            });
        }
        function getGantt_data(projectId){
            var route = "{{ route('dashboard.gantt') }}";
            var route = route+"?project_id="+projectId;
            $.get(route, function(data){
                //alert(JSON.stringify(data));
                
                google.charts.load('current', {'packages':['gantt']});
                google.charts.setOnLoadCallback(drawChart_gantt);
            
                function drawChart_gantt() {
            
                  var data = new google.visualization.DataTable();
                  data.addColumn('string', 'Task ID');
                  data.addColumn('string', 'Task Name');
                  data.addColumn('string', 'Resource');
                  data.addColumn('date', 'Start Date');
                  data.addColumn('date', 'End Date');
                  data.addColumn('number', 'Duration');
                  data.addColumn('number', 'Percent Complete');
                  data.addColumn('string', 'Dependencies');
            
                  data.addRows([
                      
                    
                      
                    ['2014Estimate', 'Estimate 2014', 'estimate',
                     new Date(2014, 2, 22), new Date(2014, 5, 20), null, 100, null],
                    ['2014Estimate1', 'Estimate1 2014', 'estimate',
                     new Date(2014, 5, 21), new Date(2014, 8, 20), null, 100, null],
                    ['2014Estimate2', 'Estimate2 2014', 'Estimate2',
                     new Date(2014, 8, 21), new Date(2014, 11, 20), null, 100, null],
                    ['Estimate 9', 'Estimate 9', 'Estimate9',
                     new Date(2015, 2, 31), new Date(2015, 18, 20), null, 14, null],
                     ['2014Estimate3', 'Estimate3 2014', 'estimate',
                     new Date(2014, 5, 21), new Date(2014, 23, 25), null, 100, null],
                    ['2014Estimate4', 'Estimate4 2014', 'Estimate4',
                     new Date(2014, 8, 21), new Date(2014, 28, 30), null, 100, null],
                    ['Estimate 12', 'Estimate 12', 'Estimate12',
                     new Date(2015, 2, 31), new Date(2015, 18, 20), null, 14, null]
                  ]);
            
                  var options = {
                    height: 400,
                    gantt: {
                      trackHeight: 30
                     
                    }
                  };
            
                  var chart = new google.visualization.Gantt(document.getElementById('gantt_div'));
            
                  chart.draw(data, options);
                }
            
            });
        }   
                  
              
      function getStatics(projectId){
          var route = "{{ route('dashboard.statics') }}";
          var route = route+"?project_id="+projectId;
          $.get(route, function(data){
            
             //alert(JSON.stringify(data.chart.estimate_week));
             //alert(JSON.stringify(data.chart.purchases_week));
            
              $(document).find('.project-statics-wrapper').html(data.html).promise().done(function(){
                if(data.chart){
                    initStatics(
                      data.chart.profit_today, 
                      data.chart.profit_monday, 
                      data.chart.profit_tuesday, 
                      data.chart.profit_wednesday, 
                      data.chart.profit_thursday, 
                      data.chart.profit_friday, 
                      data.chart.profit_saturday, 
                      data.chart.profit_sunday,
                      data.chart.rounded_high,
                      data.chart.estimate_week,
                      data.chart.purchases_week,
                      data.chart.estimate_month1,
                      data.chart.estimate_month2,
                      data.chart.estimate_month3,
                      data.chart.estimate_month4,
                      data.chart.estimate_month5,
                      data.chart.estimate_month6,
                      data.chart.estimate_month7,
                      data.chart.estimate_month8,
                      data.chart.estimate_month9,
                      data.chart.estimate_month10,
                      data.chart.estimate_month11,
                      data.chart.estimate_month12,
                      data.chart.purchases_month1,
                      data.chart.purchases_month2,
                      data.chart.purchases_month3,
                      data.chart.purchases_month4,
                      data.chart.purchases_month5,
                      data.chart.purchases_month6,
                      data.chart.purchases_month7,
                      data.chart.purchases_month8,
                      data.chart.purchases_month9,
                      data.chart.purchases_month10,
                      data.chart.purchases_month11,
                      data.chart.purchases_month12,
                      data.chart.profit_month1,
                      data.chart.profit_month2,
                      data.chart.profit_month3,
                      data.chart.profit_month4,
                      data.chart.profit_month5,
                      data.chart.profit_month6,
                      data.chart.profit_month7,
                      data.chart.profit_month8,
                      data.chart.profit_month9,
                      data.chart.profit_month10,
                      data.chart.profit_month11,
                      data.chart.profit_month12,
                    );
                }
              });
          });
      }

      function initStatics(
        profit_today, 
        profit_monday, 
        profit_tuesday, 
        profit_wednesday,
        profit_thursday,
        profit_friday,
        profit_saturday,
        profit_sunday,
        rounded_high,
        estimate_week,
        purchases_week,
        estimate_month1,
        estimate_month2,
        estimate_month3,
        estimate_month4,
        estimate_month5,
        estimate_month6,
        estimate_month7,
        estimate_month8,
        estimate_month9,
        estimate_month10,
        estimate_month11,
        estimate_month12,
        purchases_month1,
        purchases_month2,
        purchases_month3,
        purchases_month4,
        purchases_month5,
        purchases_month6,
        purchases_month7,
        purchases_month8,
        purchases_month9,
        purchases_month10,
        purchases_month11,
        purchases_month12,
        profit_month1,
        profit_month2,
        profit_month3,
        profit_month4,
        profit_month5,
        profit_month6,
        profit_month7,
        profit_month8,
        profit_month9,
        profit_month10,
        profit_month11,
        profit_month12
      ){
          
 

        dataStraightLinesChart = {
                labels: ['07', '08', '09', '10', '11', '12', '13', '14', '15'],
                series: [
                    [profit_today]
                ]
            };
            optionsStraightLinesChart = {
                lineSmooth: Chartist.Interpolation.cardinal({
                    tension: 0
                }),
                low: 0,
                high: 50,
                chartPadding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                },
                classNames: {
                    point: 'ct-point ct-white',
                    line: 'ct-line ct-white'
                }
            }

            var straightLinesChart = new Chartist.Line('#straightLinesChart', dataStraightLinesChart, optionsStraightLinesChart);

            dataRoundedLineChart = {
                labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
                series: [
                    [profit_monday, profit_tuesday, profit_wednesday, profit_thursday , profit_friday , profit_saturday, profit_sunday]
                ]
            };

            optionsRoundedLineChart = {
                lineSmooth: Chartist.Interpolation.cardinal({
                    tension: 10
                }),
                axisX: {
                    showGrid: false,
                },
                low: 0,
                high: rounded_high,
                height: '190px',
                chartPadding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                },
                showPoint: false
            }

            var RoundedLineChart = new Chartist.Line('#roundedLineChart', dataRoundedLineChart, optionsRoundedLineChart);
            //var array = JSON.parse("[" + estimate_week + "]");


            var array = estimate_week.split(",");
            const copy1 = [];
                array.forEach(function (item) { 
                copy1.push(parseInt(item)); 
            }); 
            
            var array1 = purchases_week.split(",");
            const copy2 = [];
                array1.forEach(function (item1) { 
                copy2.push(parseInt(item1)); 
            });

            dataColouredBarsChart = {
                labels: ['06', '07', '08', '09', '10', '11', '12', '13', '14', '15'],
                series: [
                  copy1,
                  copy2
                ]
            };
          

            optionsColouredBarsChart = {
                lineSmooth: Chartist.Interpolation.cardinal({
                    tension: 10
                }),
                axisY: {
                    showGrid: true,
                    offset: 40
                },
                axisX: {
                    showGrid: false,
                },
                low: 0,
                high: 1000,
                showPoint: true,
                height: '200px'
            };


            var colouredBarsChart = new Chartist.Line('#colouredBarsChart', dataColouredBarsChart, optionsColouredBarsChart);

			      var dataMultipleBarsChart = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                series: [
                    [estimate_month1, estimate_month2, estimate_month3, estimate_month4, estimate_month5, estimate_month6, estimate_month7, estimate_month8, estimate_month9, estimate_month10, estimate_month11, estimate_month12],
                    [purchases_month1, purchases_month2, purchases_month3, purchases_month4, purchases_month5, purchases_month6, purchases_month7, purchases_month8, purchases_month9, purchases_month10, purchases_month11, purchases_month12]
                ]
            };

            var optionsMultipleBarsChart = {
                seriesBarDistance: 30,
                axisX: {
                    
                    showGrid: false
                },
                height: '180px',
                width: '1100px'
               
            };

            var responsiveOptionsMultipleBarsChart = [
                ['screen and (max-width: 640px)', {
                    seriesBarDistance: 5,
                 
                    axisX: {
                        
                        labelInterpolationFnc: function(value) {
                            return value[0];
                        }
                    }
                }]
            ];

            var multipleBarsChart = Chartist.Bar('#multipleBarsChart', dataMultipleBarsChart, optionsMultipleBarsChart, responsiveOptionsMultipleBarsChart);
			
			      /*  **************** Simple Bar Chart - barchart ******************** */

           var dataSimpleBarChart = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                series: [
                    [profit_month1, profit_month2, profit_month3, profit_month4, profit_month5, profit_month6, profit_month7, profit_month8, profit_month9, profit_month10, profit_month11, profit_month12]
                ]
            };

            var optionsSimpleBarChart = {
                seriesBarDistance: 30,
                width: '1100px',
                height: '170px',
                axisX: {
                    showGrid: false
                }
            };

            var responsiveOptionsSimpleBarChart = [
                ['screen and (max-width: 640px)', {
                    seriesBarDistance: 5,
                    axisX: {
                        labelInterpolationFnc: function(value) {
                            return value[0];
                        }
                    }
                }]
            ];

            var simpleBarChart = Chartist.Bar('#simpleBarChart', dataSimpleBarChart, optionsSimpleBarChart, responsiveOptionsSimpleBarChart);

      }
    
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(carbonbarchart);

      function carbonbarchart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['',     <?php if(count($sumArray) > 0) { echo array_sum($sumArray['project_total']); }?>],
          ['',      <?php if(count($sumArray) > 0) { echo array_sum($sumArray['actualss']); } ?>],
          ['',  <?php if(count($sumArray) > 0) { if(array_sum($sumArray['project_total']) > array_sum($sumArray['actualss']) ){echo (array_sum($sumArray['project_total'])) - (array_sum($sumArray['actualss']));}else{echo 0;} } ?>]
        ]);

        var options = {
            height:250,
          title: ''
        };

        var chart = new google.visualization.PieChart(document.getElementById('carbonbarchart'));

        chart.draw(data, options);
      }
	    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(budgetChart);
       function budgetChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Total actual cost',     <?php if(count($sumArray) > 0) { echo array_sum($sumArray['project_total']); }?>],
          ['Total budgeted cost',      <?php if(count($sumArray) > 0) { echo array_sum($sumArray['actualss']); } ?>],
          ['Total cost balance',  <?php if(count($sumArray) > 0) { if(array_sum($sumArray['project_total']) > array_sum($sumArray['actualss']) ){echo (array_sum($sumArray['project_total'])) - (array_sum($sumArray['actualss']));}else{echo 0;} } ?>]
        ]);

        var options = {
            height:350,
          title: ''
        };

        var chart = new google.visualization.PieChart(document.getElementById('budgetChart'));

        chart.draw(data, options);
      }
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(cost_performance);

      function cost_performance() {
        var data = google.visualization.arrayToDataTable([
            ['Project', 'Actual Cost', 'Budgeted Cost', 'Balance'],
            
            <?php
            for($i=0;$i<count($graphs['costperformance']);$i++){
            ?>['<?php  echo $graphs['costperformance'][$i]['project_title'];  ?>',  <?php  echo $graphs['costperformance'][$i]['project_total'];  ?>,<?php  echo $graphs['costperformance'][$i]['actualss'];  ?>,<?php $dat = $graphs['costperformance'][$i]['project_total'] - $graphs['costperformance'][$i]['actualss']; echo $dat;  ?>],
            
         
            <?php } 
            ?>
          
        ]);



        var options = {
            bar: {groupWidth: '40%'},
             width: 2200,
        height: 250,

          chart: {
            title: '',
         
            

   
            subtitle: '',
            
          }
        };

        var chart = new google.charts.Bar(document.getElementById('cost_performance'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
       google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(carbon);

      function carbon() {
        var data = google.visualization.arrayToDataTable([
            ['Project', 'Actual Cost'],
            
            <?php
            for($i=0;$i<count($graphs['costperformance']);$i++){
            ?>['<?php  echo $graphs['costperformance'][$i]['project_title'];  ?>',  <?php  echo $graphs['costperformance'][$i]['project_total'];  ?>],
            
         
            <?php } 
            ?>
          
        ]);



        var options = {
            bar: {groupWidth: '40%'},
             width: 800,
        height: 250,

          chart: {
            title: '',
         
            

   
            subtitle: '',
            
          }
        };

        var chart = new google.charts.Bar(document.getElementById('carbon'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
      
      
      
      
      
      
      
      
      google.load('visualization', '1.1', {
    'packages': ['bar']
});



google.setOnLoadCallback(drawStuff);

/*function drawStuff() {
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Project');
    data.addColumn('number', 'Staff Actual');
    data.addColumn('number', 'Labour Actual');
    data.addColumn('number', 'Staff budget');
    data.addColumn('number', 'Labour budget');
   
   
    data.addRows([
        
            <?php
            if(count($graphs['stf_lbr']) > 0 ){
            for($i=0;$i<count($graphs['stf_lbr']);$i++){
            ?>['<?php  echo $graphs['stf_lbr'][$i]['title'];  ?>',  <?php  echo $graphs['stf_lbr'][$i]['staff_total_hours'];  ?>,<?php  echo $graphs['stf_lbr'][$i]['labour_spent'];  ?>,  <?php  echo $graphs['stf_lbr'][$i]['staff_estimate'];  ?>,  <?php  echo $graphs['stf_lbr'][$i]['labour_total_hours'];  ?>],
            
         
            <?php }  
                
            }
            ?>
            
        
    ]);

    // Set chart options
    var options = {
        isStacked: true,
        width: 2000,
        height: 350,
        chart: {
            title: '',
            subtitle: ''
        },
        vAxis: {
            viewWindow: {
                min: 0,
                max: 1000
            }
        },
        series: {
            2: {
                targetAxisIndex: 1
            },
            3: {
                targetAxisIndex: 1
            },
            
        }
    };

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.charts.Bar(document.getElementById('just_design'));
    chart.draw(data, google.charts.Bar.convertOptions(options));
};

  </script>

@endpush
