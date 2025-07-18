@php
    $total = 0;
    $total_h = 0;
    $total_p = 0;
    $total_hp = 0;
    
    $total_estimate = 0;
    $total_cost = 0;
    $all_total_cost = 0;
    $total_profit = 0;
    $total_loss = 0;
	
	$gtotal_cost = 0;
    $gall_total_cost = 0;
    $gtotal_estimate = 0;
    $gtotal_profit = 0;
    $gtotal_loss = 0;
	
	$total_estimate = 0;
    $total_cost = 0;
    $all_total_cost = 0;
    $all_total_hours = 0;
    $total_profit = 0;
    $total_loss = 0;	
    
    $th = 0;
    $tm = 0;
			
    $monday_total_hours = 0;
    $tuesday_total_hours = 0;
    $wednesday_total_hours = 0;
    $thursday_total_hours = 0;
    $friday_total_hours = 0;
    $saturday_total_hours = 0;
    $sunday_total_hours = 0;
			
    $monday_total_tmj = 0;
    $tuesday_total_tmj = 0;
    $wednesday_total_tmj = 0;
    $thursday_total_tmj = 0;
    $friday_total_tmj = 0;
    $saturday_total_tmj = 0;
    $sunday_total_tmj = 0;
@endphp
<div>&nbsp;</div>
 <div class="table-responsive printable-div" id="printable-div">
    <table class="table-responsive table table-bordered">
        <tr>
            <td class="text-center table-warning" width="35%">
                @if($project)
                    <p>{{ $project->company->company_name }}</p>
                    <address>
                        {!! $project->company->address_line1 ? $project->company->address_line1.'<br>' : '' !!}
                        {!! $project->company->address_line2 ? $project->company->address_line2.'<br>' : '' !!}
                        telephone:  {{ $project->company->phone }}<br>
                        fax:  {{ $project->company->fax }}<br>
                        e-mail: {{ $project->company->email }}
                    </address>
                @endif
            </td>
            <td class="table-info" width="65%">
                <table class="table-responsive table table-bordered timesheet-table" id="timesheet-table">
                    <tr>
                        <td>NAME:</td>
                        <td>{{ $supervisor->full_name ?? ''  }}</td>
                        <td>INVOICE No :</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ROLE :</td>
                        <td>
                            @if($supervisor)
                                {{ $supervisor->roles->first()->name }}
                            @endif
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Week Ending :</td>
                        <td>{{ date('M d, Y', strtotime($sunday)) }}</td>
                        <td>DATE APPROVED :</td>
                        <td></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="table table-bordered">				
        <thead class="table-primary">
            <tr class="text-center">
                <th>Project </th>
                <th>Activity code</th>
                <th>Activity</th>	 
                <th><span class="vfont">MONDAY</span></th>
                <th><span class="vfont">TUESDAY</span></th>
                <th><span class="vfont">WEDNESDAY</span></th>
                <th><span class="vfont">THURSDAY</span></th>
                <th><span class="vfont">FRIDAY</span></th>
                <th><span class="vfont">SATURDAY</span></th>
                <th><span class="vfont">SUNDAY</span></th>
                <th style="background-color: #ffffff; border: none;"></th>
                <th><span class="vfont">TOTAL</span></th>
                <th><span class="vfont">APPROVAL</span></th>
                <th><span class="vfont">SIGNATURE</span></th>     
            </tr>
        </thead>
        <tbody>
            @if($timesheets->isNotEmpty())
                @foreach($timesheets as $timesheet)
                    @php
                        $user = $timesheet->supervisor;
                        $total_hours = 0;
                        $a_total_cost = 0;
                        $thj = 0;
                        $monday_tmj = 0;
                        $tuesday_tmj = 0;
                        $wednesday_tmj = 0;
                        $thursday_tmj = 0;
                        $friday_tmj = 0;
                        $saturday_tmj = 0;
                        $sunday_tmj = 0;
                        $project_title = $timesheet->project->unique_reference_no.' '.$timesheet->project->project_title;
                        $project_ids = $timesheet->project_id;

                        $estimate = $timesheet->activityOfTimesheet->total;	
                        $per_estimate = $timesheet->selling_cost;

                        $mhr_role = $timesheet->activityOfTimesheet->mhr_role;	
                        $invoice_no = $timesheet->subActivity->sub_code;
                        $date = date("d/m/Y", strtotime($timesheet->timesheet_date));
                        $area = $timesheet->mainActivity->area;
                        $level = $timesheet->mainActivity->level;

                        $notes = $timesheet->notes;
                        $hours = $timesheet->hours;
                        $peoples = $timesheet->peoples;
                        $total_h = $total_h+ (float)$timesheet->hours;
                        $total_p = $total_p+$timesheet->peoples;
                        $total_hp = $total_hp+ (float)$timesheet->total_hours;
                        $profit = 0;
                        $loss = 0;
                        
                        $monday_hr = 0;
                        $tuesday_hr = 0;
                        $wednesday_hr = 0;
                        $thursday_hr = 0;
                        $friday_hr = 0;
                        $saturday_hr = 0;
                        $sunday_hr = 0;
                        
                        $timesheets1 = Modules\TimesheetManager\Entities\StaffTimesheet::where('project_id', $project_ids)
                                ->where('role', 'detail')
                                ->where('activity_id', $timesheet->activity_id)
                                ->whereDate('timesheet_date', $monday);

                        if($supervisor){
                            $timesheets1->where('supervisor_id', $supervisor->id);
                        }
                        $timesheets1 = $timesheets1->get();
                        
                        if($timesheets1->isNotEmpty()){
                            foreach($timesheets1 as $timesheet1){
                                $per_total_cost = $per_estimate * (float)$timesheet1->total_hours;	
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
                                $tmin = ($ph*60)+$pm;	 
                                $minpercost = $per_estimate/60;	
                                $per_total_cost = $tmin*$minpercost;   
                                $date1 = date("d/m/Y", strtotime($timesheet1->timesheet_date));	  
                                $monday_tmj = $monday_tmj+$pm;
                                $monday_hr = $monday_hr+$ph;
                                @$monday_total_hours = $monday_total_hours+$ph;
                                @$monday_a_total_cost = $monday_a_total_cost+$monday_per_total_cost;
                            }

                            if($monday_tmj > 60){
                                $monday_hr = $monday_hr+floor($monday_tmj/60);
                                $monday_total_hours = $monday_total_hours+floor($monday_tmj/60);
                                $monday_tmj= floor($monday_tmj%60);
                            }	
                                        
                            $monday_total_tmj = $monday_total_tmj+$monday_tmj;
                        }

                        $timesheets2 = Modules\TimesheetManager\Entities\StaffTimesheet::where('project_id', $project_ids)
                                ->where('role', 'detail')
                                ->where('activity_id', $timesheet->activity_id)
                                ->whereDate('timesheet_date', $tuesday);
                        if($supervisor){
                            $timesheets2->where('supervisor_id', $supervisor->id);
                        }
                        $timesheets2 = $timesheets2->get();
                        
                        if($timesheets2->isNotEmpty()){
                            foreach($timesheets2 as $timesheet2){
                                $per_total_cost = $per_estimate * (float)$timesheet2->total_hours;	
        
                                $tt = (float)$timesheet2->total_hours;	 
                                $a = '';
                                $a = (float)$timesheet2->total_hours;
                                if (strpos($a, ':') !== false) {
                                    
                                }else{
                                    $tt=str_pad($tt, 2, '0', STR_PAD_LEFT).':00';
                                }	
                                $dtm = new DateTime('2019-11-09 '.$tt.'');
                                $time = $dtm->format('H:i');
                                $ph = $dtm->format('H');
                                $pm = $dtm->format('i');
                                $tmin = ($ph*60)+$pm;	  
                                $minpercost = $per_estimate/60;	
                                $per_total_cost = $tmin*$minpercost;  
                                $date1 = date("d/m/Y", strtotime($timesheet2->timesheet_date));	 
                                $tuesday_tmj = $tuesday_tmj+$pm;
                                $tuesday_hr = $tuesday_hr+$ph;
                                @$tuesday_total_hours = $tuesday_total_hours+$ph;
                                @$tuesday_a_total_cost = $tuesday_a_total_cost+$tuesday_per_total_cost;
                            }

                            if($tuesday_tmj > 60){
                                $tuesday_hr = $tuesday_hr+floor($tuesday_tmj/60);
                                $tuesday_total_hours = $tuesday_total_hours+floor($tuesday_tmj/60);
                                $tuesday_tmj = floor($tuesday_tmj%60);
                            }	               
                            $tuesday_total_tmj = $tuesday_total_tmj+$tuesday_tmj;	
                        }

                        $timesheets3 = Modules\TimesheetManager\Entities\StaffTimesheet::where('project_id', $project_ids)
                                ->where('role', 'detail')
                                ->where('activity_id', $timesheet->activity_id)
                                ->whereDate('timesheet_date', $wednesday);
                        if($supervisor){
                            $timesheets3->where('supervisor_id', $supervisor->id);
                        }
                        $timesheets3 = $timesheets3->get();
                    
                        if($timesheets3->isNotEmpty()){
                            foreach($timesheets3 as $timesheet3){
                                $per_total_cost = $per_estimate * (float)$timesheet3->total_hours;	
        
                                $tt = (float)$timesheet3->total_hours;	 
                                $a = '';
                                $a = (float)$timesheet3->total_hours;
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
                                $date1=date("d/m/Y", strtotime($timesheet3->timesheet_date));	 
                                $wednesday_tmj = $wednesday_tmj+$pm;
                                $wednesday_hr = $wednesday_hr+$ph;
                                @$wednesday_total_hours = $wednesday_total_hours+$ph;
                                @$wednesday_a_total_cost = $wednesday_a_total_cost+$wednesday_per_total_cost;
                            }

                            if($wednesday_tmj > 60){
                                $wednesday_hr=$wednesday_hr+floor($wednesday_tmj/60);
                                $wednesday_total_hours=$wednesday_total_hours+floor($wednesday_tmj/60);
                                $wednesday_tmj=floor($wednesday_tmj%60);
                            }	
                                                
                            $wednesday_total_tmj=$wednesday_total_tmj+$wednesday_tmj;	
                        }

                        $timesheets4 = Modules\TimesheetManager\Entities\StaffTimesheet::where('project_id', $project_ids)
                                ->where('role', 'detail')
                                ->where('activity_id', $timesheet->activity_id)
                                ->whereDate('timesheet_date', $thursday);
                        if($supervisor){
                            $timesheets4->where('supervisor_id', $supervisor->id);
                        }
                        $timesheets4 = $timesheets4->get();        
                        
                        if($timesheets4->isNotEmpty()){
                            foreach($timesheets4 as $timesheet4){
                                $per_total_cost = $per_estimate * (float)$timesheet4->total_hours;	
        
                                $tt = (float)$timesheet4->total_hours;	 
                                $a = '';
                                $a = (float)$timesheet4->total_hours;
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
                                    
                                $date1 = date("d/m/Y", strtotime($timesheet4->timesheet_date));	 
                                $thursday_tmj = $thursday_tmj+$pm;
                                $thursday_hr = $thursday_hr+$ph;
                                @$thursday_total_hours = $thursday_total_hours+$ph;
                                @$thursday_a_total_cost = $thursday_a_total_cost+$thursday_per_total_cost;

                            }

                            if($thursday_tmj>60){
                                $thursday_hr=$thursday_hr+floor($thursday_tmj/60);
                                $thursday_total_hours=$thursday_total_hours+floor($thursday_tmj/60);
                                $thursday_tmj=floor($thursday_tmj%60);
                            }	

                            $thursday_total_tmj=$thursday_total_tmj+$thursday_tmj;	
                        }

                        $timesheets5 = Modules\TimesheetManager\Entities\StaffTimesheet::where('project_id', $project_ids)
                                ->where('role', 'detail')
                                ->where('activity_id', $timesheet->activity_id)
                                ->whereDate('timesheet_date', $friday);
                        if($supervisor){
                            $timesheets5->where('supervisor_id', $supervisor->id);
                        }
                        $timesheets5 = $timesheets5->get(); 

                        if($timesheets5->isNotEmpty()){
                            foreach($timesheets5 as $timesheet5){
                                $per_total_cost=$per_estimate * (float)$timesheet5->total_hours;	
        
                                $tt = (float)$timesheet5->total_hours;	 
                                $a = '';
                                $a = (float)$timesheet5->total_hours;
                                if (strpos($a, ':') !== false) {
                                    
                                }else{
                                    $tt=str_pad($tt, 2, '0', STR_PAD_LEFT).':00';
                                    
                                }	 
                                $dtm= new DateTime('2019-11-09 '.$tt.'');

                                $time=$dtm->format('H:i');
                                    
                                $ph=$dtm->format('H');
                                $pm=$dtm->format('i');

                                $tmin=($ph*60)+$pm;	
                                    
                                $minpercost=$per_estimate/60;	

                                $per_total_cost=$tmin*$minpercost;
                                    
                                $date1=date("d/m/Y", strtotime($timesheet5->timesheet_date));	 
                                $friday_tmj=$friday_tmj+$pm;
                                $friday_hr=$friday_hr+$ph;
                                @$friday_total_hours=$friday_total_hours+$ph;
                                @$friday_a_total_cost=$friday_a_total_cost+$friday_per_total_cost;
                            }

                            if($friday_tmj>60){
                                $friday_hr=$friday_hr+floor($friday_tmj/60);
                                $friday_total_hours=$friday_total_hours+floor($friday_tmj/60);
                                $friday_tmj=floor($friday_tmj%60);
                                
                            }	

                            $friday_total_tmj=$friday_total_tmj+$friday_tmj;
                        }

                        $timesheets6 = Modules\TimesheetManager\Entities\StaffTimesheet::where('project_id', $project_ids)
                                ->where('role', 'detail')
                                ->where('activity_id', $timesheet->activity_id)
                                ->whereDate('timesheet_date', $saturday);
                        if($supervisor){
                            $timesheets6->where('supervisor_id', $supervisor->id);
                        }
                        $timesheets6 = $timesheets6->get(); 

                        if($timesheets6->isNotEmpty()){
                            foreach($timesheets6 as $timesheet6){
                                
                                $per_total_cost = $per_estimate * (float)$timesheet6->total_hours;	
        
                                $tt = (float)$timesheet6->total_hours;	 
                                $a = '';
                                $a = (float)$timesheet6->total_hours;
                                if (strpos($a, ':') !== false) {
                                    
                                }else{
                                    $tt=str_pad($tt, 2, '0', STR_PAD_LEFT).':00';
                                    
                                }
                                $dtm = new DateTime('2019-11-09 '.$tt.'');

                                $time = $dtm->format('H:i');
                                    
                                $ph = $dtm->format('H');
                                $pm = $dtm->format('i');

                                $tmin = ($ph*60)+$pm;	
                                    
                                $minpercost = $per_estimate/60;	

                                $per_total_cost = $tmin*$minpercost;
                                    
                                $date1 = date("d/m/Y", strtotime($timesheet6->timesheet_date));	 
                                $saturday_tmj = $saturday_tmj+$pm;
                                $saturday_hr = $saturday_hr+$ph;
                                @$saturday_total_hours = $saturday_total_hours+$ph;
                                @$saturday_a_total_cost = $saturday_a_total_cost+$saturday_per_total_cost;
                            }
                            if($saturday_tmj>60){
                                $saturday_hr = $saturday_hr+floor($saturday_tmj/60);
                                $saturday_total_hours = $saturday_total_hours+floor($saturday_tmj/60);
                                $saturday_tmj = floor($saturday_tmj%60);
                            }	

                            $saturday_total_tmj = $saturday_total_tmj+$saturday_tmj;
                        }

                        $timesheets7 = Modules\TimesheetManager\Entities\StaffTimesheet::where('project_id', $project_ids)
                                ->where('role', 'detail')
                                ->where('activity_id', $timesheet->activity_id)
                                ->whereDate('timesheet_date', $sunday);
                        if($supervisor){
                            $timesheets7->where('supervisor_id', $supervisor->id);
                        }
                        $timesheets7 = $timesheets7->get(); 

                        if($timesheets7->isNotEmpty()){
                            foreach($timesheets7 as $timesheet7){
                                $per_total_cost=$per_estimate * (float)$timesheet7->total_hours;	
        
                                $tt= (float)$timesheet7->total_hours;	 
                                $a = '';
                                $a = (float)$timesheet7->total_hours;
                                if (strpos($a, ':') !== false) {
                                    
                                }else{
                                    $tt=str_pad($tt, 2, '0', STR_PAD_LEFT).':00';
                                    
                                }   
                                $dtm= new DateTime('2019-11-09 '.$tt.'');

                                $time=$dtm->format('H:i');
                                    
                                $ph = $dtm->format('H');
                                $pm = $dtm->format('i');

                                $tmin = ($ph*60)+$pm;	
                                    
                                $minpercost = $per_estimate/60;	

                                $per_total_cost = $tmin*$minpercost;
                                    
                                $date1=date("d/m/Y", strtotime($timesheet7->timesheet_date));	 
                                $sunday_tmj=$sunday_tmj+$pm;
                                $sunday_hr=$sunday_hr+$ph;
                                @$sunday_total_hours=$sunday_total_hours+$ph;//$rowsb['total_hours'];
                                @$sunday_a_total_cost=$sunday_a_total_cost+$sunday_per_total_cost;
                                    
                            }

                            if($sunday_tmj>60){
                                $sunday_hr=$sunday_hr+floor($sunday_tmj/60);
                                $sunday_total_hours=$sunday_total_hours+floor($sunday_tmj/60);
                                $sunday_tmj=floor($sunday_tmj%60);
                            }	

                            $sunday_total_tmj=$sunday_total_tmj+$sunday_tmj;	
                                                
                        }

                        $week_hr_total=$monday_hr+$tuesday_hr+$wednesday_hr+$thursday_hr+$friday_hr+$saturday_hr+$sunday_hr;
                        $week_tmj_total=$monday_tmj+$tuesday_tmj+$wednesday_tmj+$thursday_tmj+$friday_tmj+$saturday_tmj+$sunday_tmj;
                                            
                        if($week_tmj_total>60){
                            $week_hr_total=$week_hr_total+floor($week_tmj_total/60);
                            $week_tmj_total=floor($week_tmj_total%60);
                        }
                    @endphp
                    <tr align="center">	
                        <td>{{ $project_title }}</td>
                        <td>{{ $timesheet->activityOfTimesheet->item_code }}</td>
                        <td>{{ $invoice_no }}</td>
                        <td>
                            @if($monday_hr != 0)
                                {{ $monday_hr.':'.$monday_tmj }}
                            @endif
                        </td>
                        <td>
                            @if($tuesday_hr != 0)
                                {{ $tuesday_hr.':'.$tuesday_tmj }}
                            @endif
                        </td>
                        <td>
                            @if($wednesday_hr != 0)
                                {{ $wednesday_hr.':'.$wednesday_tmj }}
                            @endif
                        </td>
                        <td>
                            @if($thursday_hr != 0)
                                {{ $thursday_hr.':'.$thursday_tmj }}
                            @endif
                        </td>
                        <td>
                            @if($friday_hr != 0)
                                {{ $friday_hr.':'.$friday_tmj }}
                            @endif
                        </td>
                        <td>
                            @if($saturday_hr != 0)
                                {{ $saturday_hr.':'.$saturday_tmj }}
                            @endif
                        </td>
                        <td>
                            @if($sunday_hr != 0)
                                {{ $sunday_hr.':'.$sunday_tmj }}
                            @endif
                        </td>
                        <td style="background-color: #ffffff; border: none;">&nbsp; &nbsp;</td>
                        
                        <td>{{ $week_hr_total.':'.$week_tmj_total }}</td>
                        <td>
                            @if($timesheet->approver)
                                &#10004;
                            @endif
                        </td>
                        <td>
                            @if($timesheet->approver)
                                {{ $timesheet->approver->full_name }}
                                <small><br>{{ date("d/m/Y", strtotime($timesheet->approval_date)) }}</small>
                            @endif
                        </td>
                    </tr>
                @endforeach
                @php
                    if($all_total_cost > $total_estimate)
                    {
                        $total_loss=$all_total_cost - $total_estimate;
                    }
                    elseif($all_total_cost < $total_estimate)
                    {
                        $total_profit= $total_estimate - $all_total_cost;
                    }	 
        
                    $gall_total_cost=$gall_total_cost+$all_total_cost;
                    $gtotal_estimate=$gtotal_estimate+$total_estimate;
                    $gtotal_profit=$gtotal_profit+$total_profit;
                    $gtotal_loss=$gtotal_loss+$total_loss;	
                    if($monday_total_tmj>60){
                        $monday_total_hours=$monday_total_hours+floor($monday_total_tmj/60);
                        $monday_total_tmj=floor($monday_total_tmj%60);
                    }	
                
                    if($tuesday_total_tmj>60){
                        $tuesday_total_hours=$tuesday_total_hours+floor($tuesday_total_tmj/60);
                        $tuesday_total_tmj=floor($tuesday_total_tmj%60);
                    }	
                
                    if($wednesday_total_tmj>60){
                        $wednesday_total_hours=$wednesday_total_hours+floor($wednesday_total_tmj/60);
                        $wednesday_total_tmj=floor($wednesday_total_tmj%60);
                    }	
                
                    if($thursday_total_tmj>60){
                        $thursday_total_hours=$thursday_total_hours+floor($thursday_total_tmj/60);
                        $thursday_total_tmj=floor($thursday_total_tmj%60);
                    }	
                
                    if($friday_total_tmj>60){
                        $friday_total_hours=$friday_total_hours+floor($friday_total_tmj/60);
                        $friday_total_tmj=floor($friday_total_tmj%60);
                    }	
                
                    if($saturday_total_tmj>60){
                        $saturday_total_hours=$saturday_total_hours+floor($saturday_total_tmj/60);
                        $saturday_total_tmj=floor($saturday_total_tmj%60);
                    }	
                
                    if($sunday_total_tmj>60){
                        $sunday_total_hours=$sunday_total_hours+floor($sunday_total_tmj/60);
                        $sunday_total_tmj=floor($sunday_total_tmj%60);
                    }			
                
                    $week_total_hours=$monday_total_hours+$tuesday_total_hours+$wednesday_total_hours+$thursday_total_hours+$friday_total_hours+$saturday_total_hours+$sunday_total_hours;
                
                    $week_total_hours_tmj=$monday_total_tmj+$tuesday_total_tmj+$wednesday_total_tmj+$thursday_total_tmj+$friday_total_tmj+$saturday_total_tmj+$sunday_total_tmj;
                
                    if($week_total_hours_tmj>60){
                    $week_total_hours=$week_total_hours+floor($week_total_hours_tmj/60);
                    $week_total_hours_tmj=floor($week_total_hours_tmj%60);
                    }
                @endphp
                <tr align="center">
                    <td  colspan="3" align="right"><p><b>TOTALS : </b></td>
                    <td><p><b>{{ $monday_total_hours.':'.$monday_total_tmj }}</b></p></td>
                    <td><p><b>{{ $tuesday_total_hours.':'.$tuesday_total_tmj }}</b></p></td>
                    <td><p><b>{{ $wednesday_total_hours.':'.$wednesday_total_tmj }}</b></p></td>
                    <td><p><b>{{ $thursday_total_hours.':'.$thursday_total_tmj }}</b></p></td>
                    <td><p><b>{{ $friday_total_hours.':'.$friday_total_tmj }}</b></p></td>
                    <td><p><b>{{ $saturday_total_hours.':'.$saturday_total_tmj }}</b></p></td>
                    <td><p><b>{{ $sunday_total_hours.':'.$sunday_total_tmj }}</b></p></td>
                    <td style="background-color: #ffffff; border: none;"><p>&nbsp; &nbsp; </p></td>
                    <td><p><b>{{ $week_total_hours.':'.$week_total_hours_tmj }}</b></p></td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
