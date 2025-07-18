<?php

namespace Modules\TimesheetManager\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ProjectManager\Entities\Project;
use Modules\EstimateManager\Entities\Activity;
use Modules\EstimateManager\Entities\SubActivity;
use Modules\EstimateManager\Entities\MainActivity;
use Modules\TimesheetManager\Entities\StaffTimesheet;
use Modules\TimesheetManager\Entities\LabourTimesheet;


class TimesheetManagerAjaxController extends Controller
{

    /**
     * Get the staff timesheet.
     * @param Request $request
     * @return Response
     */
    public function staffTimesheets(Request $request){
        $view = "";
        if($request->ajax()){
            if($request->query('project')){
                $project = Project::findOrFail($request->query('project'));
                $query = $project->staffTimesheets();
          
                $query->when(($request->query('area') && !empty($request->query('area'))), function($q) use($request){
                    $q->where('main_activity_id', $request->query('area'));
                });
                $query->when(($request->query('level') && !empty($request->query('level'))), function($q) use($request){
                    $q->where('main_activity_id', $request->query('level'));
                });
                $query->where('role', 'head');
                $query->groupBy('activity_id');
                $timesheets = $query->get();

                $view = view('timesheetmanager::staff_timesheet_list', compact('timesheets'))->render();
            }
        }
        return response()->json(['html'=> $view]);
    }

    /**
     * Get the labour timesheet.
     * @param Request $request
     * @return Response
     */
    public function labourTimesheets(Request $request){
        $view = "";
        if($request->ajax()){
            if($request->query('project')){
                $project = Project::findOrFail($request->query('project'));
                $query = $project->labourTimesheets();
                $query->when(($request->query('area') && !empty($request->query('area'))), function($q) use($request){
                    $q->where('main_activity_id', $request->query('area'));
                });
                $query->when(($request->query('level') && !empty($request->query('level'))), function($q) use($request){
                    $q->where('main_activity_id', $request->query('level'));
                });

                $timesheets = $query->get();
                
                $view = view('timesheetmanager::labour_timesheet_list', compact('timesheets'))->render();
            }
        }
        
        return response()->json(['html'=> $view]);
    }

    /**
     * Get the staff weekly timesheet.
     * @param Request $request
     * @return Response
     */
    public function staffWeeklyTimesheets(Request $request){
        $view = "";
        if($request->ajax()){
            $query = StaffTimesheet::where('role', 'detail');
            $from = "";
            $supervisor_id = '';
            $supervisor_user = '';
            $supervisor_user_role = '';
            if($request->query('date')){
                $from = $request->query('date');
                $from = str_replace("/","-",$from);
                $from = date("Y-m-d", strtotime($from));
                $dd = date('D', strtotime($from));
                if($dd != "Mon"){
                    $di = 0;
                    while($dd != "Mon"){
                        $di = $di+1;
                        $dd = date('D', strtotime($from. ' + '.$di.' days'));
                        
                        $monday = date('Y-m-d', strtotime($from. ' + '.$di.' days'));
                    }
                }else{
                    $monday = $from;
                }

                $tuesday = date('Y-m-d', strtotime($monday. ' + 1 days'));
                $wednesday = date('Y-m-d', strtotime($tuesday. ' + 1 days'));
                $thursday = date('Y-m-d', strtotime($wednesday. ' + 1 days'));
                $friday = date('Y-m-d', strtotime($thursday. ' + 1 days'));
                $saturday = date('Y-m-d', strtotime($friday. ' + 1 days'));
                $sunday = date('Y-m-d', strtotime($saturday. ' + 1 days'));
                
                $query->whereBetween('timesheet_date', [$monday, $sunday]);

            }

            if($request->query('project')){
                $query->whereIn('project_id', [$request->query('project')]);
                $project = Project::find($request->query('project'));
                $project_title = $project->unique_reference_no.' '.$project->project_title;	
            }
            
            if($request->query('user')){
                $query->where('supervisor_id', $request->query('user'));
                $user = \App\User::where('id', $request->query('user'))->first(['id', 'full_name']);
                if($user){
                    $supervisor_id = $user->id;
                    $supervisor_user = $user->email;
                    $role = $user->roles->first();
                    $supervisor_name = ucfirst($user->full_name);
                    if($role){
                        $supervisor_role = ucfirst($role->name);
                    }
                }
            }
            $query->groupBy('activity_id');

            $timesheets = $query->get();

            $view = view('timesheetmanager::staff_weekly_timesheet_list', compact(
                'project',
                'timesheets',
                'supervisor_id',
                'supervisor_user',
                'supervisor_name',
                'supervisor_role',
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday'
            ))->render();
        }
        return response()->json(['html'=> $view]);
    }
    
    /**
     * Get the labour weekly timesheet.
     * @param Request $request
     * @return Response
     */
    public function labourWeeklyTimesheets(Request $request){
        $view = "";
        if($request->ajax()){
            //$query = LabourTimesheet::where('role', 'detail');
            //$query = LabourTimesheet::where('id', '>', 0);
            $query = LabourTimesheet::join('labour_timesheet_materials', 'labour_timesheet_materials.labour_timesheet_id', '=', 'labour_timesheets.id');
            //$query =  DB::table('labour_timesheets')->join('labour_timesheet_materials', 'labour_timesheets.id', '=', 'labour_timesheet_materials.labour_timesheet_id')->select('*');

            
            $from = "";
            $supervisor_id = '';
            $supervisor_user = '';
            $supervisor_user_role = '';
            if($request->query('date')){
                $from = $request->query('date');
                $from = str_replace("/","-",$from);
                $from = date("Y-m-d", strtotime($from));
                $dd = date('D', strtotime($from));
                if($dd != "Mon"){
                    $di = 0;
                    while($dd != "Mon"){
                        $di = $di+1;
                        $dd = date('D', strtotime($from. ' + '.$di.' days'));
                        
                        $monday = date('Y-m-d', strtotime($from. ' + '.$di.' days'));
                    }
                }else{
                    $monday = $from;
                }

                $tuesday = date('Y-m-d', strtotime($monday. ' + 1 days'));
                $wednesday = date('Y-m-d', strtotime($tuesday. ' + 1 days'));
                $thursday = date('Y-m-d', strtotime($wednesday. ' + 1 days'));
                $friday = date('Y-m-d', strtotime($thursday. ' + 1 days'));
                $saturday = date('Y-m-d', strtotime($friday. ' + 1 days'));
                $sunday = date('Y-m-d', strtotime($saturday. ' + 1 days'));
                
                $query->whereBetween('timesheet_date', [$monday, $sunday]);

            }

            if($request->query('project')){
                $query->whereIn('project_id', [$request->query('project')]);
                $project = Project::find($request->query('project'));
                $project_title = $project->unique_reference_no.' '.$project->project_title;	
            }
            
            if($request->query('user')){
                $query->where('operative', $request->query('user'));
                $user = \App\User::where('full_name', $request->query('user'))->first(['id', 'full_name','email','rate']);
                
                if($user){
                    $supervisor_id = $user->id;
                    $supervisor_user = $user->email;
                    $role = $user->roles->first();
                    $supervisor_name = ucfirst($user->full_name);
                    $rate = $user->rate;
                    if($role){
                        $supervisor_role = ucfirst($role->name);
                    }
                }
            }
            $query->groupBy('activity_id');


            $timesheets = $query->get();
//print_r($query->getBindings());
//return $timesheets;

            $view = view('timesheetmanager::labour_weekly_timesheet_list', compact(
                'project',
                'timesheets',
                'supervisor_id',
                'supervisor_user',
                'supervisor_name',
                'supervisor_role',
                'rate',
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday'
            ))->render();
        }
        return response()->json(['html'=> $view]);
    }

    /**
     * Get staff timesheet from.
     * @param Request $request
     * @return Response
     */
    public function getTimesheetStaffForm(Request $request){
        $view = "";
        if($request->ajax()){
            $subActivity = SubActivity::find($request->query('sub_activity_id'));
            if($subActivity){
                $query = $subActivity->activities();
                $query->whereIn('unit', ['mhr', 'dhr']);
                if(!auth()->user()->isRole('Super Admin') && !auth()->user()->isRole('Admin')){
                    $role = auth()->user()->roles()->first();
                    $query->where('mhr_role', $role->name);
                }
                $query->where('mhr_status', 0);
                $activities = $query->get();

                $view = view('timesheetmanager::staff_timesheet_add_form', compact('activities'))->render();
            }
            
        }
        
        return response()->json(['html'=> $view]);
    }

    /**
     * Get labour timesheet from.
     * @param Request $request
     * @return Response
     */
    public function getTimesheetLabourForm(Request $request){
        $view = "";
        if($request->ajax()){
            
            $subActivity = SubActivity::find($request->query('sub_activity_id'));
            if($subActivity){
                $query = $subActivity->activities();
                $query->whereNotIn('unit', ['mhr', 'dhr', 'nr', 'nrp', '']);
                $activities = $query->get();

                $view = view('timesheetmanager::labour_timesheet_add_form', compact('activities'))->render();
            }
            
        }
        
        return response()->json(['html'=> $view]);
    }
    
    /**
     * Get labour timesheet from.
     * @param Request $request
     * @return Response
     */
    public function approveStaffTimesheet(Request $request){
        if($request->ajax()){
            try{
                \DB::beginTransaction();
                $projectId = $request->query('project_id');
                $activityId = $request->query('activity_id');
                $monday = $request->query('monday');
                $sunday = $request->query('sunday');
                $supervisor = $request->query('supervisor');
                if($projectId && $activityId && $monday && $sunday && $supervisor){
                    $timesheets = StaffTimesheet::where('role', 'detail')
                                        ->where('project_id', $projectId)
                                        ->where('activity_id', $activityId)
                                        ->where('activity_id', $activityId)
                                        ->where('supervisor_email', $supervisor)
                                        ->whereBetween('timesheet_date', [$monday, $sunday])
                                        ->get();
                    if($timesheets->isNotEmpty()){
                        foreach($timesheets as $timesheet){
                            $timesheet->approver_id = auth()->id();
                            $timesheet->approval_date = date('Y-m-d');
                            $timesheet->save();
                        }
                    }
                }
                // commit database
                \DB::commit();
                return response()->json(['status'=> true, 'message'=> 'Timesheet has been approved successfully.']);
            }catch(\Exception $e){
                \DB::rollBack();
            } 
        }
        return response()->json(['status'=> false, 'message'=> 'Somthing went wrong. Please try again later.']);
    }
    
        /**
     * Get labour timesheet from.
     * @param Request $request
     * @return Response
     */
    public function approveLabourTimesheet(Request $request){
        
        if($request->ajax()){
          
            try{
                \DB::beginTransaction();
                $projectId = $request->query('project_id');
                $activityId = $request->query('activity_id');
                $monday = $request->query('monday');
                $sunday = $request->query('sunday');
                $supervisor = $request->query('supervisor');
                if($projectId && $activityId && $monday && $sunday && $supervisor){
                    
                    $timesheets = LabourTimesheet::join('labour_timesheet_materials', 'labour_timesheet_materials.labour_timesheet_id', '=', 'labour_timesheets.id')
                                        ->where('project_id', $projectId)
                                        ->where('activity_id', $activityId)
                                        ->where('operative', $supervisor)
                                        ->whereBetween('timesheet_date', [$monday, $sunday])->get();
               
                    if($timesheets->isNotEmpty()){
                        
                        foreach($timesheets as $timesheet){
                            $affected = DB::table('labour_timesheet_materials')
                            ->where('id', $timesheet->id)
                            ->update(['approver_id' => auth()->id(), 'approval_date' =>date('Y-m-d')]);
                            
                        }
                    }
                }
                
                // commit database
                \DB::commit();
                return response()->json(['status'=> true, 'message'=> 'Timesheet has been approved successfully.']);
            }catch(\Exception $e){
               
                \DB::rollBack();
            } 
        }
        return response()->json(['status'=> false, 'message'=> 'Somthing went wrong. Please try again later.']);
    }
    

}
