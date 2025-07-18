<?php

namespace Modules\TimesheetManager\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ProjectManager\Entities\Project;
use Modules\TimesheetManager\Entities\StaffTimesheet;
use Modules\TimesheetManager\Entities\LabourTimesheet;
use Modules\TimesheetManager\Entities\StaffTimesheetFile;
use Modules\TimesheetManager\Entities\LabourTimesheetFile;
use Modules\TimesheetManager\Entities\LabourTimesheetMaterial;

class TimesheetManagerController extends Controller
{
    /**
     * Staff timesheet.
     * @return Response
     */
    public function staffTimesheets(Request $request, $id = null)
    {
        if (!auth()->user()->can('access', 'timesheets visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $query = Project::whereStatus(1);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
				$query =DB::table('users_project')
				->join('projects', 'projects.id', '=', 'users_project.project_id')
				->select('projects.id As id','projects.company_id AS company_id','projects.project_title AS project_title','projects.status AS status','projects.version As version','users_project.users_id')
				->where([
				['company_id', auth()->user()->company_id],
				['users_project.users_id', auth()->id()],
				['status',1]
			   ]);
                //$query->where('company_id', auth()->user()->company_id);
            }
        }
        if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin'))){
        $allProjects = $query->get();
        $allProjects = $allProjects->pluck('project_title', 'id');
         $allProjects->prepend('Select Project', '');
		}else{
		$allProjects = $query->get(['id', 'project_title', 'version']);
        $allProjects = $allProjects->pluck('display_project_title', 'id');
        $allProjects->prepend('Select Project', '');
		}
        

        $user=auth()->user();
        if(isset($user->default_project) && !isset($request->change))
        {
       $project = Project::where('id',$user->default_project)->count();

       if($project)
       {
        $id=$user->default_project;
        
       }

        }


      if(isset($request->change))
        {
       $project = Project::where('id',$request->change)->count();

       if($project)
       {
        $id=$request->change;
        
       }

        }

        if($id){
            $project = Project::findOrFail($id);
            $areas = $project->mainActivities->pluck('area_display_name', 'id');
            $areas->prepend('All', '');

            $levels = $project->mainActivities->pluck('level_display_name', 'id');
            $levels->prepend('All', '');

            return view('timesheetmanager::staff_timesheets', compact(
                'allProjects', 
                'project',
                'areas',
                'levels',
                'user'
            ));
        } 
        return view('timesheetmanager::staff_timesheets', compact('allProjects','user'));
    }

    /**
     * Labour weekly timesheet..
     * @return Response
     */
    public function labourTimesheets(Request $request, $id = null)
    {
        if (!auth()->user()->can('access', 'timesheets visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $query = Project::whereStatus(1);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
				$query =DB::table('users_project')
				->join('projects', 'projects.id', '=', 'users_project.project_id')
				->select('projects.id As id','projects.company_id AS company_id','projects.project_title AS project_title','projects.status AS status','projects.version As version','users_project.users_id')
				->where([
				['company_id', auth()->user()->company_id],
				['users_project.users_id', auth()->id()],
				['status',1]
			   ]);
                //$query->where('company_id', auth()->user()->company_id);
            }
        }
        if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin'))){
        $allProjects = $query->get();
        $allProjects = $allProjects->pluck('project_title', 'id');
         $allProjects->prepend('Select Project', '');
		}else{
		$allProjects = $query->get(['id', 'project_title', 'version']);
        $allProjects = $allProjects->pluck('display_project_title', 'id');
        $allProjects->prepend('Select Project', '');
		}

        $user=auth()->user();
        if(isset($user->default_project) && !isset($request->change))
        {
       $project = Project::where('id',$user->default_project)->count();

       if($project)
       {
        $id=$user->default_project;
        
       }

        }


      if(isset($request->change))
        {
       $project = Project::where('id',$request->change)->count();

       if($project)
       {
        $id=$request->change;
        
       }

        }

        if($id){
            $project = Project::findOrFail($id);
            $areas = $project->mainActivities->pluck('area_display_name', 'id');
            $areas->prepend('All', '');

            $levels = $project->mainActivities->pluck('level_display_name', 'id');
            $levels->prepend('All', '');
            return view('timesheetmanager::labour_timesheets', compact(
                'allProjects', 
                'project',
                'areas',
                'levels',
                'user'
            ));
        } 
        return view('timesheetmanager::labour_timesheets', compact('allProjects','user'));
    }

    /**
     * Staff weekly timesheet.
     * @return Response
     */
    public function staffTimesheetsWeekly(Request $request)
    {
        if (!auth()->user()->can('access', 'timesheets visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $query = Project::whereStatus(1);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
				$query =DB::table('users_project')
				->join('projects', 'projects.id', '=', 'users_project.project_id')
				->select('projects.id As id','projects.company_id AS company_id','projects.project_title AS project_title','projects.status AS status','projects.version As version','users_project.users_id')
				->where([
				['company_id', auth()->user()->company_id],
				['users_project.users_id', auth()->id()],
				['status',1]
				]);
               // $query->where('company_id', auth()->user()->company_id);
            }
        }
        if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin'))){
        $allProjects = $query->get();
        $allProjects = $allProjects->pluck('project_title', 'id');
         $allProjects->prepend('Select Project', '');
		}else{
		$allProjects = $query->get(['id', 'project_title', 'version']);
        $allProjects = $allProjects->pluck('display_project_title', 'id');
        $allProjects->prepend('Select Project', '');
		}

        $allTimesheets = StaffTimesheet::where('role', 'detail')->whereNotNull('supervisor_id')->distinct()->get(['id', 'supervisor_id']);
        
        $allUsers = [''=> 'Select user'];
        if($allTimesheets->isNotEmpty()){
            foreach($allTimesheets as $timesheet){
                $supervisor = $timesheet->supervisor;
                if($supervisor){
                    $allUsers[$supervisor->id] = $supervisor->full_name;
                }
            }
        }
        $user=auth()->user();
        return view('timesheetmanager::staff_timesheets_weekly', compact('allProjects', 'allUsers','user'));
    }

    /**
     * Labour weekly timesheet.
     * @return Response
     */
    public function labourTimesheetsWeekly(Request $request)
    {
        if (!auth()->user()->can('access', 'timesheets visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $query = Project::whereStatus(1);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
				$query =DB::table('users_project')
				->join('projects', 'projects.id', '=', 'users_project.project_id')
				->select('projects.id As id','projects.company_id AS company_id','projects.project_title AS project_title','projects.status AS status','projects.version As version','users_project.users_id')
				->where([
				['company_id', auth()->user()->company_id],
				['users_project.users_id', auth()->id()],
				['status',1]
			]);
                //$query->where('company_id', auth()->user()->company_id);
            }
        }
       if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin'))){
        $allProjects = $query->get();
        $allProjects = $allProjects->pluck('project_title', 'id');
         $allProjects->prepend('Select Project', '');
		}else{
		$allProjects = $query->get(['id', 'project_title', 'version']);
        $allProjects = $allProjects->pluck('display_project_title', 'id');
        $allProjects->prepend('Select Project', '');
		}

        //$allTimesheets = LabourTimesheet::where('role', 'detail')->whereNotNull('supervisor_id')->distinct()->get(['id', 'supervisor_id']);
        
        
        
    $all_labour = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->select('users.*', 'role_user.role_id')->where('role_user.role_id',5);
            
      if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $all_labour->where('users.company_id', auth()->id());
            }else{
                $all_labour->where('users.company_id', auth()->user()->company_id);
            }
        }
       
         $allTimesheets = $all_labour->get();
            
          
        $allUsers = [''=> 'Select user'];
        if($allTimesheets->isNotEmpty()){
            foreach($allTimesheets as $timesheet){
               
                
                    $allUsers[$timesheet->full_name] = $timesheet->full_name;
                
            }
        }
        $user=auth()->user();
        return view('timesheetmanager::labour_timesheets_weekly', compact('allProjects', 'allUsers','user'));
    }
    /**
     * Create a staff time sheet.
     * @return Response
     */
    public function createStaffTimesheet(Request $request, $id){
        if (!auth()->user()->can('access', 'timesheets add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $project = Project::findOrFail($id);
        $areas = $project->mainActivities->pluck('area_display_name', 'id');
        $areas->prepend('Select area', '');

        return view('timesheetmanager::create_staff_timesheet', compact(
            'project',
            'areas'
        )); 
    }

    /**
     * Store staff timesheet.
     * @return Response
     */
    public function storeStaffTimesheet(Request $request, $id){
        if (!auth()->user()->can('access', 'timesheets add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try{
            $all_date = explode(",",$request->date);
            
            
            foreach($all_date as $dates){
                
                DB::beginTransaction();
                if($request->has('activities')){
                    $total_hours = '';
                    $activity_code = '';
                    $sub_code = '';
                    $notes = '';
                    $total_peoples = 0;
                    $start_time_main = '';
                    $end_time_main = '';
                    $all_total_hours = '';
                    $total_cost = 0;
                    $all_total_cost = 0;
                    $all_selling_cost = 0;
                    $activity_id = 0;
                    $time2 = '00:00';
                    $th = 0;
                    $tm = 0;
                    $i = 0;
                    $activities = (array)$request->activities;
                    $selectedRows = (array)$request->selected_rows;
                    foreach($selectedRows as $selectedRow){
                    //foreach($activities as $activity){
                        
                        $project_id                 = $activities[$selectedRow]['project_id'];
                        $main_activity_id           = $activities[$selectedRow]['main_activity_id'];
                        $sub_activity_id            = $activities[$selectedRow]['sub_activity_id'];
                        $activity_id                = $activities[$selectedRow]['activity_id'];
                        $activity                   = $activities[$selectedRow]['activity'];
                        $date                       = $dates;
                        $supervisor_id              = auth()->id();
                        $notes                      = $request->notes ?? '';
                        $start_time                 = $activities[$selectedRow]['start_time'];
                        $end_time                   = $activities[$selectedRow]['end_time'];
                        $hours                      = $activities[$selectedRow]['hours'];
                        $peoples                    = $activities[$selectedRow]['peoples'];
                        $each_total_hours           = $activities[$selectedRow]['total_hours'];
                        $selling_cost               = $activities[$selectedRow]['selling_cost'];
                        $total_cost                 = (float)$activities[$selectedRow]['total_hours'] * (float)$activities[$selectedRow]['selling_cost'];
                        $dtm                        = \Carbon\Carbon::parse('2019-11-09 '.$activities[$selectedRow]['total_hours'].'');
                        $time                       = $dtm->format('H:i');
                        $ph                         = $dtm->format('H');
                        $pm                         = $dtm->format('i');
                        $tmin                       = ($ph*60) + $pm;	
                        $minpercost                 = $activities[$selectedRow]['selling_cost']/60;	
    
                        $total_cost                 = $tmin*$minpercost;
                            
                        $secs                       = strtotime($time)-strtotime("00:00");
                        $time2                      = date("H:i",strtotime($time2)+$secs);	
                            
                        $th                         = $th+$ph;
                        $tm                         = $tm+$pm;
    
                        $total_peoples              = $total_peoples+$peoples;
                        $all_total_hours            = $time2;	
                            
                        $all_total_cost             = $all_total_cost+$total_cost;	
                        $all_selling_cost           = $all_selling_cost+$selling_cost;
                        if($i == 0){
                            $start_time_main        = $start_time;
                        }
                        $end_time_main              = $end_time;
                        $total_peoples              = $total_peoples+$peoples;;
                        $all_total_hours            = $time2;	
                            
                        $all_total_cost             = $all_total_cost+$total_cost;	
                        $all_selling_cost           = $all_selling_cost+$selling_cost;
    
                        $detailStafTimesheet        = new StaffTimesheet();
                        $detailStafTimesheet->project_id        = $project_id;
                        $detailStafTimesheet->main_activity_id  = $main_activity_id;
                        $detailStafTimesheet->sub_activity_id   = $sub_activity_id;
                        $detailStafTimesheet->activity_id       = $activity_id;
                        $detailStafTimesheet->supervisor_id     = $supervisor_id;
                        $detailStafTimesheet->activity          = $activity;
                        $detailStafTimesheet->timesheet_date    = $date;
                        $detailStafTimesheet->start_time        = $start_time;
                        $detailStafTimesheet->end_time          = $end_time;
                        $detailStafTimesheet->hours             = $hours;
                        $detailStafTimesheet->peoples           = $peoples;
                        $detailStafTimesheet->total_hours       = $each_total_hours;
                        $detailStafTimesheet->selling_cost      = $selling_cost;
                        $detailStafTimesheet->total_cost        = $total_cost;
                        $detailStafTimesheet->notes             = $notes;
                        $detailStafTimesheet->home              = $request->home;
                        $detailStafTimesheet->office            = $request->office;
                        $detailStafTimesheet->hybrid            = $request->hybrid;
                        $detailStafTimesheet->walking           = $request->walking;
                        $detailStafTimesheet->cycling           = $request->cycling;
						$detailStafTimesheet->public_transport  = $request->public_transport;
                        $detailStafTimesheet->car               = $request->car;
                        $detailStafTimesheet->hybrid_commute    = $request->hybrid_commute;
                        $detailStafTimesheet->walking_text      = $request->walking_text;
                        $detailStafTimesheet->cycling_text      = $request->cycling_text;
                        $detailStafTimesheet->public_transport_text      = $request->public_transport_text;
                        $detailStafTimesheet->car_transport_text         = $request->car_transport_text;
                        $detailStafTimesheet->hybrid_text                = $request->hybrid_text;
                        $detailStafTimesheet->home_energy                = $request->home_energy;
                        $detailStafTimesheet->office_energy              = $request->office_energy;
                        $detailStafTimesheet->hybrid_energy              = $request->hybrid_energy;
						$detailStafTimesheet->electricity                = $request->electricity;
                        $detailStafTimesheet->gas                        = $request->gas;
                        $detailStafTimesheet->laptop                     = $request->laptop;
                        $detailStafTimesheet->desktop                    = $request->desktop;
                        $detailStafTimesheet->others                     = $request->others;
                        $detailStafTimesheet->laptop_kwh                 = $request->laptop_kwh;
                        $detailStafTimesheet->desktop_kwh                = $request->desktop_kwh;
                        $detailStafTimesheet->others_kwh                 = $request->others_kwh;
                        $detailStafTimesheet->role              = 'detail';
                        $detailStafTimesheet->save();
                        
                        if($tm > 60){
                            $th = $th+floor($tm/60);
                            $tm = floor($tm%60);
                        }	
                        
                        $th = str_pad($th, 2, '0', STR_PAD_LEFT);	 
                        $tm = str_pad($tm, 2, '0', STR_PAD_LEFT);		
                            
                        $all_total_hours = $th.':'.$tm;	
                        $total_hours = $all_total_hours;
    
                        $headStafTimesheet                  = new StaffTimesheet();
                        $headStafTimesheet->project_id      = $project_id;
                        $headStafTimesheet->main_activity_id= $main_activity_id;
                        $headStafTimesheet->sub_activity_id = $sub_activity_id;
                        $headStafTimesheet->activity_id     = $activity_id;
                        $headStafTimesheet->supervisor_id   = $supervisor_id;
                        $headStafTimesheet->activity        = $activity;
                        $headStafTimesheet->timesheet_date  = $date;
                        $headStafTimesheet->start_time      = $start_time_main;
                        $headStafTimesheet->end_time        = $end_time_main;
                        $headStafTimesheet->hours           = $total_hours;
                        $headStafTimesheet->peoples         = $total_peoples;
                        $headStafTimesheet->total_hours     = $all_total_hours;
                        $headStafTimesheet->selling_cost    = $all_selling_cost;
                        $headStafTimesheet->total_cost      = $all_total_cost;
                        $headStafTimesheet->notes           = $notes;
                        $headStafTimesheet->role            = 'head';
						$headStafTimesheet->home              = $request->home;
                        $headStafTimesheet->office            = $request->office;
                        $headStafTimesheet->hybrid            = $request->hybrid;
                        $headStafTimesheet->walking           = $request->walking;
                        $headStafTimesheet->cycling           = $request->cycling;
						$headStafTimesheet->public_transport  = $request->public_transport;
                        $headStafTimesheet->car               = $request->car;
                        $headStafTimesheet->hybrid_commute    = $request->hybrid_commute;
                        $headStafTimesheet->walking_text      = $request->walking_text;
                        $headStafTimesheet->cycling_text      = $request->cycling_text;
                        $headStafTimesheet->public_transport_text      = $request->public_transport_text;
                        $headStafTimesheet->car_transport_text         = $request->car_transport_text;
                        $headStafTimesheet->hybrid_text                = $request->hybrid_text;
                        $headStafTimesheet->home_energy                = $request->home_energy;
                        $headStafTimesheet->office_energy              = $request->office_energy;
                        $headStafTimesheet->hybrid_energy              = $request->hybrid_energy;
						$headStafTimesheet->electricity                = $request->electricity;
                        $headStafTimesheet->gas                        = $request->gas;
                        $headStafTimesheet->laptop                     = $request->laptop;
                        $headStafTimesheet->desktop                    = $request->desktop;
                        $headStafTimesheet->others                     = $request->others;
                        $headStafTimesheet->laptop_kwh                 = $request->laptop_kwh;
                        $headStafTimesheet->desktop_kwh                = $request->desktop_kwh;
                        $headStafTimesheet->others_kwh                 = $request->others_kwh;
                        $headStafTimesheet->save();
    
                        if($headStafTimesheet){
                            if($request->hasFile('site_diaries')){
                                $files = $request->file('site_diaries');
                                foreach ($files as $file) {
                                    $extension = $file->getClientOriginalExtension();
                                    $filename  = str_random(10).'-' . time() . '.' . $extension;
                                    $file->storeAs('timesheet_files/site_diaries', $filename, 'public');
                                    $staffTimesheetFile = new StaffTimesheetFile();
                                    $staffTimesheetFile->staff_timesheet_id = $headStafTimesheet->id;
                                    $staffTimesheetFile->category = "site_diaries";
                                    $staffTimesheetFile->file = $filename;
                                    $staffTimesheetFile->save();
                                }
                            }
                            if($request->hasFile('images')){
                                $files = $request->file('images');
                                foreach ($files as $file) {
                                    $extension = $file->getClientOriginalExtension();
                                    $filename  = str_random(10).'-' . time() . '.' . $extension;
                                    $file->storeAs('timesheet_files/images', $filename, 'public');
                                    $staffTimesheetFile = new StaffTimesheetFile();
                                    $staffTimesheetFile->staff_timesheet_id = $headStafTimesheet->id;
                                    $staffTimesheetFile->category = "images";
                                    $staffTimesheetFile->file = $filename;
                                    $staffTimesheetFile->save();
                                }
                            }
                            if($request->hasFile('person_photos')){
                                $files = $request->file('person_photos');
                                foreach ($files as $file) {
                                    $extension = $file->getClientOriginalExtension();
                                    $filename  = str_random(10).'-' . time() . '.' . $extension;
                                    $file->storeAs('timesheet_files/person_photo', $filename, 'public');
                                    $staffTimesheetFile = new StaffTimesheetFile();
                                    $staffTimesheetFile->staff_timesheet_id = $headStafTimesheet->id;
                                    $staffTimesheetFile->category = "person_photo";
                                    $staffTimesheetFile->file = $filename;
                                    $staffTimesheetFile->save();
                                }
                            }
                            if($request->hasFile('drawings')){
                                $files = $request->file('drawings');
                                foreach ($files as $file) {
                                    $extension = $file->getClientOriginalExtension();
                                    $filename  = str_random(10).'-' . time() . '.' . $extension;
                                    $file->storeAs('timesheet_files/drawings', $filename, 'public');
                                    $staffTimesheetFile = new StaffTimesheetFile();
                                    $staffTimesheetFile->staff_timesheet_id = $headStafTimesheet->id;
                                    $staffTimesheetFile->category = "drawings";
                                    $staffTimesheetFile->file = $filename;
                                    $staffTimesheetFile->save();
                                }
                            }
                        }
						
						
                        $i++;
                    }
                }
            // commit database
            DB::commit();
            }
            
            
            return redirect()->route('timesheets.staff', $id)->with('success', 'This timesheet has been created successfully!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('timesheets.staff', $id)->with('error', 'Somthing went wrong. Please try again later.');
        }
    }

    /**
     * Edit staff timesheet.
     * @return Response
     */
    public function editStaffTimesheet(Request $request, $id){
        if (!auth()->user()->can('access', 'timesheets add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $timesheet = StaffTimesheet::findOrFail($id);
        return view('timesheetmanager::edit_staff_timesheet', compact('timesheet')); 
    }
/**
     * Edit staff timesheet.
     * @return Response
     */
    public function editprintStaffTimesheet(Request $request, $id){
        if (!auth()->user()->can('access', 'timesheets add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $timesheet = StaffTimesheet::findOrFail($id);
        return view('timesheetmanager::edit_print_staff_timesheet', compact('timesheet')); 
    }
    /**
     * Update staff timesheet.
     * @return Response
     */
    public function updateStaffTimesheet(Request $request, $id){
        if (!auth()->user()->can('access', 'timesheets add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try{
            DB::beginTransaction();
            $timesheet = StaffTimesheet::findOrFail($id);
            $start_time = $request->start_time;
            $end_time = $request->end_time;
            $hours = $request->hours;
            $total_hours = $request->total_hours;
            $notes = $request->notes;
            $date = $request->timesheet_date;	
            $selling_cost = $request->selling_cost;	

            $dtm = \Carbon\Carbon::parse('2019-11-09 '.$hours.'');
            $time = $dtm->format('H:i');
                
            $ph = $dtm->format('H');
            $pm = $dtm->format('i');

            $tmin = ($ph*60)+$pm;	
                
            $minpercost = $selling_cost/60;	

            $total_cost = $tmin*$minpercost;

						$timesheet->hours = $hours;
						$timesheet->total_hours = $total_hours;
						$timesheet->start_time = $start_time;
						$timesheet->end_time = $end_time;
						$timesheet->timesheet_date = $date;
						$timesheet->total_cost = $total_cost;
						$timesheet->notes = $notes;
						$timesheet->home              = $request->home;
                        $timesheet->office            = $request->office;
                        $timesheet->hybrid            = $request->hybrid;
                        $timesheet->walking           = $request->walking;
                        $timesheet->cycling           = $request->cycling;
						$timesheet->public_transport  = $request->public_transport;
                        $timesheet->car               = $request->car;
                        $timesheet->hybrid_commute    = $request->hybrid_commute;
                        $timesheet->walking_text      = $request->walking_text;
                        $timesheet->cycling_text      = $request->cycling_text;
                        $timesheet->public_transport_text      = $request->public_transport_text;
                        $timesheet->car_transport_text         = $request->car_transport_text;
                        $timesheet->hybrid_text                = $request->hybrid_text;
                        $timesheet->home_energy                = $request->home_energy;
                        $timesheet->office_energy              = $request->office_energy;
                        $timesheet->hybrid_energy              = $request->hybrid_energy;
						$timesheet->electricity                = $request->electricity;
                        $timesheet->gas                        = $request->gas;
                        $timesheet->laptop                     = $request->laptop;
                        $timesheet->desktop                    = $request->desktop;
                        $timesheet->others                     = $request->others;
                        $timesheet->laptop_kwh                 = $request->laptop_kwh;
                        $timesheet->desktop_kwh                = $request->desktop_kwh;
                        $timesheet->others_kwh                 = $request->others_kwh;
                        $timesheet->save();

            // commit database
            DB::commit();
            return redirect()->route('timesheets.staff', $timesheet->project_id)->with('success', 'This timesheet has been updated successfully!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', 'Somthing went wrong. Please try again later.');
        }
    }

    /**
     * Delete staff timesheet.
     * @return Response
     */
    public function deleteStaffTimesheet(Request $request, $id){
        if (!auth()->user()->can('access', 'timesheets add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $timesheet = StaffTimesheet::findOrFail($id);
        try{
            DB::beginTransaction();
            // Delete timesheet
            $timesheet->delete();
            // Commit database
            DB::commit();

            return redirect()->route('timesheets.staff', $timesheet->project_id)->with('success', 'This timesheet has been deleted successfully!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('timesheets.staff', $timesheet->project_id)->with('error', 'Somthing went wrong. Please try again later.');
        }
    }

    /**
     * Print staff timesheet.
     * @return Response
     */
    public function printStaffTimesheet(Request $request, $id){
        $timesheet = StaffTimesheet::findOrFail($id);
        return view('timesheetmanager::print_staff_timesheet', compact('timesheet'));
    }

    /**
     * Gallery staff timesheet.
     * @return Response
     */
    public function galleryStaffTimesheet(Request $request, $id){
        $timesheet = StaffTimesheet::findOrFail($id);
        return view('timesheetmanager::gallery_staff_timesheet', compact('timesheet'));
    }

    /**
     * Download staff timesheet.
     * @return Response
     */
    public function downloadStaffTimesheetFile(Request $request, $id){
        try{
            $timesheetFile = StaffTimesheetFile::findOrFail($id);
            $name = "staff_timesheet_file_".time();
            return response()->download(storage_path('app/public/timesheet_files/'.$timesheetFile->category.'/'.$timesheetFile->file), $name);
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Somthing went wrong. Please try again later.');
        }
    }

    /**
     * Create a labour time sheet.
     * @return Response
     */
    public function createLabourTimesheet(Request $request, $id){
        if (!auth()->user()->can('access', 'timesheets add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $project = Project::findOrFail($id);
        $areas = $project->mainActivities->pluck('area_display_name', 'id');
        $areas->prepend('Select area', '');
        
        
        $all_labour = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->select('users.*', 'role_user.role_id')->where('role_user.role_id',5);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $all_labour->where('users.company_id', auth()->id());
            }else{
                $all_labour->where('users.company_id', auth()->user()->company_id);
            }
        }
        $labour_list = $all_labour->get();
         
        return view('timesheetmanager::create_labour_timesheet', compact(
            'project',
            'areas',
            'labour_list'
        )); 
    }
    
    /**
     * Create a labour time sheet.
     * @return Response
     */
    public function createseperateLabourTimesheet(Request $request, $id){
        if (!auth()->user()->can('access', 'timesheets add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $project = Project::findOrFail($id);
        $areas = $project->mainActivities->pluck('area_display_name', 'id');
        $areas->prepend('Select area', '');
        
        
        $all_labour = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->select('users.*', 'role_user.role_id')->where('role_user.role_id',5);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $all_labour->where('users.company_id', auth()->id());
            }else{
                $all_labour->where('users.company_id', auth()->user()->company_id);
            }
        }
        $labour_list = $all_labour->get();
         
        return view('timesheetmanager::create_labour_timesheet_seperate', compact(
            'project',
            'areas',
            'labour_list'
        )); 
    }

    /**
     * Store labour timesheet.
     * @return Response
     */
    public function storeLabourTimesheet(Request $request, $id){
        if (!auth()->user()->can('access', 'timesheets add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try{
            $all_date = explode(",",$request->date);
            
            
            foreach($all_date as $dates){
            DB::beginTransaction();
            if($request->has('activities')){
                
                $activities = (array)$request->activities;
                $selectedRows = (array)$request->selected_rows;
                foreach($selectedRows as $selectedRow){
                    $timesheet                      = new LabourTimesheet();
                    $timesheet->project_id          = $activities[$selectedRow]['project_id'];
                    $timesheet->main_activity_id    = $request->area;
                    $timesheet->sub_activity_id     = $request->sub_code;
                    $timesheet->activity_id         = $activities[$selectedRow]['activity_id'];
                    $timesheet->supervisor_id       = auth()->id();
                    $timesheet->activity            = $activities[$selectedRow]['activity'];
                    $timesheet->peoples             = $activities[$selectedRow]['peoples'];
                    $timesheet->allocated_hour      = $activities[$selectedRow]['allocated_hour'];
                    $timesheet->total_spent_hour    = $activities[$selectedRow]['total_spent_hour'];
                    $timesheet->remaining_hour      = $activities[$selectedRow]['remaining_hour'];
                    $timesheet->spent_hour          = $activities[$selectedRow]['spent_hour'];
                    $timesheet->timesheet_date      = $dates;
                    $timesheet->notes               = $activities[$selectedRow]['notes'];
                    $timesheet->save();
                    if($timesheet){
                        if(\Arr::has($activities[$selectedRow], 'materials')){
                            $materials = (array)$activities[$selectedRow]['materials'];
                            foreach($materials as $material){
                                $material['labour_timesheet_id'] = $timesheet->id;
                                $timesheetMaterial = LabourTimesheetMaterial::create($material);
                            }
                        }
                        if(\Arr::has($activities[$selectedRow], 'files')){
                            $files = (array)$activities[$selectedRow]['files'];
                            foreach ($files as $file) {
                                $extension = $file->getClientOriginalExtension();
                                $filename  = str_random(10).'-' . time() . '.' . $extension;
                                $file->storeAs('timesheet_files', $filename, 'public');
                                $labourTimesheetFile = new LabourTimesheetFile();
                                $labourTimesheetFile->labour_timesheet_id = $timesheet->id;
                                $labourTimesheetFile->category = "labour_files";
                                $labourTimesheetFile->file = $filename;
                                $labourTimesheetFile->save();
                            }
                        }
                    }
                }
            }
            // commit database
            DB::commit();
            }
            
            return redirect()->route('timesheets.labour', $id)->with('success', 'This timesheet has been created successfully!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', 'Somthing went wrong. Please try again later.');
        }
    }
    
    /**
     * Store labour timesheet.
     * @return Response
     */
    public function storeseperateLabourTimesheet(Request $request, $id){
       
        if (!auth()->user()->can('access', 'timesheets add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try{
            $all_date = explode(",",$request->date);
            
            
            foreach($all_date as $dates){
            DB::beginTransaction();
            if($request->has('activities')){
                
                $activities = (array)$request->activities;
               
                $selectedRows = (array)$request->selected_rows;
                foreach($selectedRows as $selectedRow){
                    $timesheet                      = new LabourTimesheet();
                    $timesheet->project_id          = $activities[$selectedRow]['project_id'];
                    $timesheet->main_activity_id    = $request->area;
                    $timesheet->sub_activity_id     = $request->sub_code;
                    $timesheet->activity_id         = $activities[$selectedRow]['activity_id'];
                    $timesheet->supervisor_id       = auth()->id();
                    $timesheet->activity            = $activities[$selectedRow]['activity'];
                    $timesheet->peoples             = $activities[$selectedRow]['peoples'];
                    $timesheet->allocated_hour      = $activities[$selectedRow]['allocated_hour'];
                    $timesheet->total_spent_hour    = $activities[$selectedRow]['total_spent_hour'];
                    $timesheet->remaining_hour      = $activities[$selectedRow]['remaining_hour'];
                    $timesheet->spent_hour          = $activities[$selectedRow]['spent_hour'];
                    $timesheet->timesheet_date      = $dates;
                    $timesheet->notes               = $activities[$selectedRow]['notes'];
                    $timesheet->save();
                    if($timesheet){
                        if(\Arr::has($activities[$selectedRow], 'materials')){
                            $materials = (array)$activities[$selectedRow]['materials'];
                            foreach($materials as $material){
                                $material['labour_timesheet_id'] = $timesheet->id;
                                $timesheetMaterial = LabourTimesheetMaterial::create($material);
                            }
                        }
                        if(\Arr::has($activities[$selectedRow], 'files')){
                            $files = (array)$activities[$selectedRow]['files'];
                            foreach ($files as $file) {
                                $extension = $file->getClientOriginalExtension();
                                $filename  = str_random(10).'-' . time() . '.' . $extension;
                                $file->storeAs('timesheet_files', $filename, 'public');
                                $labourTimesheetFile = new LabourTimesheetFile();
                                $labourTimesheetFile->labour_timesheet_id = $timesheet->id;
                                $labourTimesheetFile->category = "labour_files";
                                $labourTimesheetFile->file = $filename;
                                $labourTimesheetFile->save();
                            }
                        }
                    }
                }
            }
            // commit database
            DB::commit();
            }
            
            return redirect()->route('timesheets.labour', $id)->with('success', 'This timesheet has been created successfully!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', 'Somthing went wrong. Please try again later.');
        }
       
    }
    
    /**
     * Edit labour timesheet.
     * @return Response
     */
    public function editLabourTimesheet(Request $request, $id){
        if (!auth()->user()->can('access', 'timesheets add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $timesheet = LabourTimesheet::findOrFail($id);
        
        $all_labour = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->select('users.*', 'role_user.role_id')->where('role_user.role_id',5);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $all_labour->where('users.company_id', auth()->id());
            }else{
                $all_labour->where('users.company_id', auth()->user()->company_id);
            }
        }
        $labour_list = $all_labour->get();
        
        return view('timesheetmanager::edit_labour_timesheet', compact('timesheet','labour_list')); 
    }

    /**
     * Update labour timesheet.
     * @return Response
     */
    public function updateLabourTimesheet(Request $request, $id){
        if (!auth()->user()->can('access', 'timesheets add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $timesheet = LabourTimesheet::findOrFail($id);
        try{
            DB::beginTransaction();

            $peoples = $request->peoples;
            $allocated_hour = $request->allocated_hour;
            $spent_hour = $request->spent_hour;	
            $remaining_hour = $request->remaining_hour;	
            $total_spent_hour = $request->total_spent_hour;	

            $timesheet->allocated_hour = $allocated_hour;
            $timesheet->spent_hour = $spent_hour;
            $timesheet->total_spent_hour = $total_spent_hour;
            $timesheet->remaining_hour = $remaining_hour;
            $timesheet->peoples = $peoples;
            $timesheet->notes = $request->notes;
            $timesheet->save();

            $totalth = strtok($total_spent_hour, ':');
            $totaltm = substr(strstr($total_spent_hour, ':'), 1); 

            $allth = strtok($allocated_hour, ':');
            $alltm = substr(strstr($allocated_hour, ':'), 1);	 

            $restTimesheets = LabourTimesheet::where('project_id', $timesheet->project->id)
                                ->where('activity_id', $timesheet->activity_id)
                                ->where('id', '>', $timesheet->id)
                                ->get();

            if($restTimesheets->isNotEmpty()){
                foreach($restTimesheets as $restTimesheet){
                    $up_spent_hour = $restTimesheet->spent_hour;
                    $upth = strtok($up_spent_hour, ':');
                    $uptm = substr(strstr($up_spent_hour, ':'), 1);
                    $totalth = $totalth + $upth;
                    $totaltm = $totaltm + $uptm;
                                
                    if($totaltm > 60){
                        $totalth = $totalth+floor($totaltm/60);
                        $totaltm = floor($totaltm%60);
                    }	  
                    $up_total_spent_hour = $totalth.':'.$totaltm;

                    if($totalth!=00){
                        $up_remaining_hour = ($allth-$totalth).':'.abs($alltm-$totaltm);	
                    }else{
                        $up_remaining_hour = '00:00';	
                    }

                    $restTimesheet->total_spent_hour = $up_total_spent_hour;
                    $restTimesheet->remaining_hour = $up_remaining_hour;

                    $restTimesheet->save();
                }
            }

            // update materials
            if($request->has('materials')){
                $materials = (array)$request->materials;
                foreach($materials as $material){
                    $timesheetMaterial = LabourTimesheetMaterial::findOrFail($material['id']);
                    $timesheetMaterial->update($material);
                }
            }
            // update files
            if($request->has('files')){
                $files = $request->file('files');
                foreach ($files as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename  = str_random(10).'-' . time() . '.' . $extension;
                    $file->storeAs('timesheet_files', $filename, 'public');
                    $labourTimesheetFile = new LabourTimesheetFile();
                    $labourTimesheetFile->labour_timesheet_id = $timesheet->id;
                    $labourTimesheetFile->category = "labour_files";
                    $labourTimesheetFile->file = $filename;
                    $labourTimesheetFile->save();
                }
            }
            // commit database
            DB::commit();
            return redirect()->route('timesheets.labour', $timesheet->project_id)->with('success', 'This timesheet has been updated successfully!');
        }catch(\Exception $e){
            DB::rollBack();
            dd($e);
            return redirect()->route('timesheets.labour', $timesheet->project_id)->with('error', 'Somthing went wrong. Please try again later.');
        }
    }

    /**
     * Delete labour timesheet.
     * @return Response
     */
    public function deleteLabourTimesheet(Request $request, $id){
        if (!auth()->user()->can('access', 'timesheets add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $timesheet = LabourTimesheet::findOrFail($id);
        try{
            DB::beginTransaction();
            $allocated_hour = "00:00";
            $total_spent_hour = "00:00";	
            $projectId = $timesheet->project->id;
            $timesheetId = $timesheet->id;
            $activity_id = $timesheet->activity_id;
            $allocated_hour = $timesheet->allocated_hour;
            // Delete timesheet
            $timesheet->delete();

            $restTimesheet = LabourTimesheet::where('project_id', $projectId)
                                ->where('activity_id', $activity_id)
                                ->where('id', '<', $timesheetId)
                                ->orderBy('id', 'DESC')
                                ->first();

            if($restTimesheet){
                $activity_id = $restTimesheet->activity_id;
                $allocated_hour = $restTimesheet->allocated_hour;
                $total_spent_hour = $restTimesheet->total_spent_hour;
                $spent_hour = $restTimesheet->spent_hour;
            }

            $totalth = strtok($total_spent_hour, ':');
            $totaltm = substr(strstr($total_spent_hour, ':'), 1); 

            $allth = strtok($allocated_hour, ':');
            $alltm = substr(strstr($allocated_hour, ':'), 1);
            
            $restTimesheets = LabourTimesheet::where('project_id', $projectId)
                                ->where('activity_id', $activity_id)
                                ->where('id', '<', $timesheetId)
                                ->get();
            
            if($restTimesheets->isNotEmpty()){
                foreach($restTimesheets as $restTimesheet1){
                    $up_spent_hour = $restTimesheet1->spent_hour;
                    $upth = strtok($up_spent_hour, ':');
                    $uptm = substr(strstr($up_spent_hour, ':'), 1); 
                    $totalth = $totalth + $upth;
                    $totaltm = $totaltm + $uptm;
                    if($totaltm > 60){
                        $totalth = $totalth+floor($totaltm/60);
                        $totaltm = floor($totaltm%60);
                    }	 
                    $up_total_spent_hour = $totalth.':'.$totaltm;

                    if($totalth != 00 || $totalth != 0){
                        $up_remaining_hour = ($allth-$totalth).':'.abs($alltm-$totaltm);	
                    }else{
                        $up_remaining_hour = '00:00';	
                    }
                    $restTimesheet1->total_spent_hour = $up_total_spent_hour;
                    $restTimesheet1->remaining_hour = $up_remaining_hour;
                    $restTimesheet1->save();
                }
            }

            DB::commit();

            return redirect()->route('timesheets.labour', $timesheet->project_id)->with('success', 'This timesheet has been deleted successfully!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('timesheets.labour', $timesheet->project_id)->with('error', 'Somthing went wrong. Please try again later.');
        }
    }


    /**
     * Print labour timesheet.
     * @return Response
     */
    public function printLabourTimesheet(Request $request, $id){
        $timesheet = LabourTimesheet::findOrFail($id);
        return view('timesheetmanager::print_labour_timesheet', compact('timesheet'));
    }

    /**
     * Gallery labour timesheet.
     * @return Response
     */
    public function galleryLabourTimesheet(Request $request, $id){
        $timesheet = LabourTimesheet::findOrFail($id);
      
        return view('timesheetmanager::gallery_labour_timesheet', compact('timesheet'));
    }
    
    

    /**
     * Download labour timesheet.
     * @return Response
     */
    public function downloadLabourTimesheetFile(Request $request, $id){
        try{
            $timesheetFile = LabourTimesheetFile::findOrFail($id);
            $name = "labour_timesheet_file_".time();
            return response()->download(storage_path('app/public/timesheet_files/'.$timesheetFile->file), $name);
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Somthing went wrong. Please try again later.');
        }
    }
    
   
}
