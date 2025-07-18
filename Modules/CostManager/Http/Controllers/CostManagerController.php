<?php

namespace Modules\CostManager\Http\Controllers;

use App\User;
use App\Task;
use App\Link;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\Utilities\Request as DatatableRequest;
use Modules\ProjectManager\Entities\Project;
use Modules\EstimateManager\Http\Requests\ImportActivityRequest;
use App\Imports\ImportCostActivity;
use Modules\EstimateManager\Entities\MainActivity;
use DateTime;

class CostManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        
        //return 1;
        
        /*if (!auth()->user()->can('access', 'admins visible') && (!auth()->user()->isRole('Super Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }*/
       //$data['project'] = \DB::table('projects')->select('*')->get()->toarray();
        /*$categories = Category::where('status', 1)
                ->pluck('name', 'id');
        $categories->prepend('All', '');*/
        
        
        $query = Project::join('users', 'users.id', '=', 'projects.company_id');
        /*if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
                $query->where('company_id', auth()->user()->company_id);
            }
        }*/
        
        



//$query->join('users', 'users.id', '=', 'projects.company_id');


        $projects = $query->get(['projects.id', 'projects.project_title', 'projects.version','projects.unique_reference_no','projects.company_id','projects.location','projects.type_of_contract','projects.shifts','projects.tender_status','projects.base_margin','projects.status','projects.current_start_date','projects.current_completion_date','users.company_name'])->toarray();
        
        
        //$projects = $projects->pluck('display_project_title', 'id');
    
    
    /*foreach($projects as $key=>$proj){
        $query8 = Project::query();
        $query8->where('id', $proj['id']);
        $projects8 = $query8->get();
        foreach($projects8 as $por){
            $estimate = 0;
            foreach($por->mainActivities as $mainActivity){
         
            $estimate =  $estimate + $mainActivity->total;
            }
           $projects[$key]['estimate']= $estimate; 
        }
      
     
    }*/ 
    
    
 foreach($projects as $keys=>$proj){
     $projects[$keys]['Actuals']=0;
        $query3 = Project::query();
        $query3->where('id', $proj['id']);
        $projectss = $query3->get();
        foreach($projectss as $project){
$k_tmj=0;
$k_total_hours=0;
$k_a_total_cost=0;
$hr=0;
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
                
                    if($project->mainActivities->isNotEmpty()){
                        foreach($project->mainActivities as $mainActivity){
                           
                                $estimate =  ($estimate + $mainActivity->total);
                                $main_activity_id = $mainActivity->id;
                                $project_total =  $project_total + $mainActivity->total;
                                $project_hr_total =  $project_hr_total + $mainActivity->total_hr;
                                $project_mhr_total =  $project_mhr_total + $mainActivity->total_mhr;
                            
                            if($mainActivity->subActivities->isNotEmpty()){
                                foreach($mainActivity->subActivities as $subActivity){
                                    if($subActivity->activities->isNotEmpty()){
                                        foreach($subActivity->activities as $activity){
                                          
                                                $unit_trim = preg_replace('/\s+/', '', $activity->unit);
                                                 
                                                $formulas = $project->formulas->where('keyword', '!=', '');
                                         
                                            if($formulas->isNotEmpty()){
                                                foreach($formulas as $formula){
                                                
                                                if($unit_trim == $formula->keyword){
                                                 
                                                        $labour_total = $labour_total + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                        $labour_total_hour = $labour_total_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                                        $fq[$unit_trim] = @$fq[$unit_trim]+($activity->quantity * $subActivity->quantity * $mainActivity->quantity);
                                                        $fk[$unit_trim] = @$fk[$unit_trim] + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                        $labourTimesheets = $project->labourTimesheets->where('activity_id', $activity->id);
                                                    
                                                
                                                    if($labourTimesheets->isNotEmpty()){
                                                        foreach($labourTimesheets as $labourTimesheet){
                                                            if($labourTimesheet->timesheetMaterials->isNotEmpty()){
                                                                foreach($labourTimesheet->timesheetMaterials as $timesheetMaterial){
                                                              
                                                                   
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
                                                                
                                                                        $k_tmj = ($k_tmj+$k_pm);
                                                                        /*$k_total_hours = ($k_total_hours+$k_ph);
                                                                        $k_a_total_cost = ($k_a_total_cost+$k_per_total_cost);
                                                                    
                                                                        $fqka[$unit_trim] = ($fqka[$unit_trim]+$k_per_total_cost);
                                                                        $fqkah[$unit_trim] = ($fqkah[$unit_trim]+$k_ph);*/
                                                                 
                                                                }
                                                                
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
                                                             
                                                            }
                                                        }
                                                    }
                                                    
                                                    
                                                    
                                                    
                                                }    
                                                }

                                            }
                                            
                                            if($unit_trim == "nrp"){
                                               
                                                    $plant_cost = $plant_cost + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                    $plant_cost_hour = $plant_cost_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                              
                                            }
                                            if($unit_trim == "nr"){
                                             
                                                    $material_cost = $material_cost + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                    $material_cost_hour = $material_cost_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                                 
                                            }
                                            if($unit_trim == "mhr")	{
                                               
                                                    $manager_total = $manager_total + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                    $manager_total_hour = $manager_total_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                                    $mhr_estimate = $activity->total;
                                                    $mhr_per_estimate = $activity->selling_cost;

                                                    $staffTimesheets = $project->staffTimesheets->where('role', 'detail')->where('activity_id', $activity->id);
                                               
                                                if($staffTimesheets->isNotEmpty()){
                                                    foreach($staffTimesheets as $staffTimesheet){
                                                   
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
                                                            $mhr_total_hours = ($mhr_total_hours+$ph);;
                                                            $mhr_a_total_cost = ($mhr_a_total_cost+$mhr_per_total_cost);
                                                       
                                                    }
                                                  
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
                                                  
                                                }
                                            }
                                            if($unit_trim == "dhr"){
                                                
                                                    $design_total = $design_total + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                    $design_total_hour = $design_total_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                                    $dhr_estimate = $activity->total;
                                                    $dhr_per_estimate = $activity->selling_cost;

                                                    $staffTimesheets = $project->staffTimesheets->where('role', 'detail')->where('activity_id', $activity->id);
                                              
                                                if($staffTimesheets->isNotEmpty()){
                                                    foreach($staffTimesheets as $staffTimesheet){
                                                    
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
                                                            $dhr_total_hours = ($dhr_total_hours+$dhr_ph);
                                                            $dhr_a_total_cost = ($dhr_a_total_cost+$dhr_per_total_cost);
                                                          
                                                    }
                                                   
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
                                                  
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        $projects[$keys]['estimate']= $estimate;
                        }}
                    if($project->purchases->isNotEmpty()){
                        foreach($project->purchases as $purchase){
                            if($purchase->orders->isNotEmpty()){
                                foreach($purchase->orders as $order){
                                     
                                        $purchases =  ($purchases + $order->total);
                                  
                                }
                            }
                           
                                $purchases = ($purchases + $purchase->carriage_costs	+ $purchase->c_of_c	+ $purchase->other_costs);
                           
                        }
                    }
                  
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
                   
                    foreach($fq as $key=>$value){	
                     
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
                       
                    }
                   
                     $actual_project_total = ($actual_project_total + $purchases);
                 //echo  $actual_project_total.'-----**-----';  
        }
    
     $projects[$keys]['Actuals']= $actual_project_total;
     
 }
                    
                  //print_r($projects);exit;
                    
     
        return view('costmanager::index', compact('projects'));
    }
    
    
    


}
