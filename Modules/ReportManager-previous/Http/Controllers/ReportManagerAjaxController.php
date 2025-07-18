<?php

namespace Modules\ReportManager\Http\Controllers;

use App\Role;
use App\User;
use App\Projectuser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ProjectManager\Entities\Project;
use Modules\EstimateManager\Entities\Activity;
use Modules\EstimateManager\Entities\SubActivity;
use Modules\EstimateManager\Entities\MainActivity;
use Modules\TimesheetManager\Entities\StaffTimesheet;
use Modules\TimesheetManager\Entities\LabourTimesheet;


class ReportManagerAjaxController extends Controller
{

    /**
     * Get the project report.
     * @param Request $request
     * @return Response
     */
    public function projectReports(Request $request){
        $view = "";
        if($request->ajax()){
            try{
                $query = Project::query();
                if(!auth()->user()->isRole('Super Admin')){
                    if(auth()->user()->isRole('Admin')){
                        $query->where('company_id', auth()->id());
                    }else{
                        $query->where('company_id', auth()->user()->company_id);
                    }
                }
                $query->when(($request->has('project_id')), function($q) use($request){
                    $q->where('id', $request->query('project_id'));
                });
                $projects = $query->get();
                $view = view('reportmanager::project_report_list', compact('projects'))->render();
            }catch(\Exception $e){}
        }
        return response()->json(['html'=> $view]);
    }

    /**
     * Get the purchase report.
     * @param Request $request
     * @return Response
     */
    public function purchaseReports(Request $request){
        $view = "";
        if($request->ajax()){
            try{
                $project = Project::where('id', $request->query('project_id'))->first();
                if($project){
                    $dates = [];
                    $query = $project->purchases();
                    if($request->query('date_form') && $request->query('date_to')){
                        $date['form'] = $request->query('date_form');
                        $date['to'] = $request->query('date_to');
                        $query->where('delivery_date', [$date['form'], $date['to']]);
                    }
                    $purchases = $query->groupBy('sub_activity_id')->orderBy('delivery_date', 'DESC')->get();
                    $view = view('reportmanager::purchase_report_list', compact('project', 'purchases', 'dates'))->render();
                }  
            }catch(\Exception $e){

            }
        }
       
        return response()->json(['html'=> $view]);
    }

    /**
     * Get the staff timesheet report.
     * @param Request $request
     * @return Response
     */
    public function staffTimesheetReports(Request $request){
        $view = "";
        if($request->ajax()){
            try{
                $allTimesheets = StaffTimesheet::where('role', 'detail')->whereNotNull('supervisor_id')->distinct()->get(['id', 'supervisor_id']);
                $users = [''=> 'User'];
                if($allTimesheets->isNotEmpty()){
                    foreach($allTimesheets as $timesheet){
                        $supervisor = $timesheet->supervisor;
                        if($supervisor){
                            $users[$supervisor->id] = $supervisor->full_name;
                        }
                    }
                }
                $roles = Role::select('name')
                    ->whereNotIn('slug', ['super_admin', 'admin', 'supplier'])
                    ->where('status', 1)
                    ->get();
                $roles = $roles->sortBy('name');
                $roles = $roles->mapWithKeys(function ($item) {
                    return [$item['name'] => $item['name']];
                });
                $roles->prepend('Role', '');
                $view = view('reportmanager::staff_timesheet_report_list', compact('users', 'roles'))->render();
            }catch(\Exception $e){}

        }
        return response()->json(['html'=> $view]);
    }

    /**
     * Get the staff timesheet report info.
     * @param Request $request
     * @return Response
     */
    public function staffTimesheetReportInfo(Request $request){
        $view = "";
        if($request->ajax()){
            try{
                $project = Project::where('id', $request->query('project_id'))->first();
                if($project){
                    $staffTimesheets = $project->staffTimesheets()
                       ->where('role', 'head')
                        ->orderBy('id', 'DESC')
                        ->get();
                    $view = view('reportmanager::staff_timesheet_report_list_info', compact('project', 'staffTimesheets'))->render();
                }
            }catch(\Exception $e){}

        }
        return response()->json(['html'=> $view]);
    }

    /**
     * Get the weekly staff timesheet report.
     * @param Request $request
     * @return Response
     */
    public function weeklyStaffTimesheetReports(Request $request){
        $view = "";
        if($request->ajax()){
            try{
                $project = Project::where('id', $request->query('project_id'))->first();
                if($project){
                    $allTimesheets = StaffTimesheet::where('role', 'detail')->whereNotNull('supervisor_id')->distinct()->get(['id', 'supervisor_id']);
                    $users = [''=> 'User'];
                    if($allTimesheets->isNotEmpty()){
                        foreach($allTimesheets as $timesheet){
                            $supervisor = $timesheet->supervisor;
                            if($supervisor){
                                $users[$supervisor->id] = $supervisor->full_name;
                            }
                        }
                    }
                    $view = view('reportmanager::weekly_staff_timesheet_report_list', compact('project', 'users'))->render();
                }
            }catch(\Exception $e){}
        }
        return response()->json(['html'=> $view]);
    }

    /**
     * Get the labours timesheet report info.
     * @param Request $request
     * @return Response
     */
    public function labourTimesheetReportInfo(Request $request){
        $view = "";
        if($request->ajax()){
            try{
                $project = Project::where('id', $request->query('project_id'))->first();
                if($project){
                    $labourTimesheets = $project->labourTimesheets()
                        /*->whereRaw('id = (select max(id) from labour_timesheets)')*/
                        ->groupBy('activity_id')
                        ->orderBy('id', 'DESC')
                        ->get();
                    $view = view('reportmanager::labour_timesheet_report_list_info', compact('project', 'labourTimesheets'))->render();
                }
            }catch(\Exception $e){}
        }
        return response()->json(['html'=> $view]);
    }

    /**
     * Get the sfatt timesheet report filter.
     * @param Request $request
     * @return Response
     */
    public function staffTimesheetReportFilter(Request $request){
        $view = "";
        if($request->ajax()){
            try{
                $today = date('Y-m-d');
                $from = "";
                $to = "";
                $project = '';
                $supervisor = '';

                $query = StaffTimesheet::where('role', 'detail');
                if($request->form_date && $request->to_date){
                    $from = $request->form_date;
                    $from = str_replace("/","-",$from);
                    $from = date("Y-m-d", strtotime($from));
    
                    $to = $request->to_date;
                    $to = str_replace("/","-",$to);
                    $to = date("Y-m-d", strtotime($to));
                    $query->when(($from && $to), function($q) use($from, $to){
                        $q->whereBetween('timesheet_date', [$from, $to]);
                    });
                }
                
                if($request->project_id){
                    $projectId = $request->project_id;
                    $project = $project = Project::find($projectId);
                    $query->when($projectId, function($q) use($projectId){
                        $q->where('project_id', $projectId);
                    });
                }

                if($request->user_id){
                    $userId = $request->user_id;
                    $supervisor = User::find($userId);
                    $query->when($userId, function($q) use($userId){
                        $q->where('supervisor_id', $userId);
                    });
                }
                $timesheets = $query->groupBy('activity_id')->orderBy('id', 'DESC')->get();
                if($project){
                    $view = view('reportmanager::staff_timesheet_report_filter_list', compact('project', 'timesheets', 'from', 'to'))->render();
                }
                
            }catch(\Exception $e){}
        }
        return response()->json(['html'=> $view]);
    }
    
    /**
     * Get the weekly sfatt timesheet report filter.
     * @param Request $request
     * @return Response
     */
    public function weeklyStaffTimesheetReportFilter(Request $request){
        $view = "";
        if($request->ajax()){
            //try{
                $from = "";
                $project = '';
                $supervisor = '';
                
                $query = StaffTimesheet::where('role', 'detail');
                if($request->query('form_date')){
                    $from = $request->query('form_date');
                    $from = str_replace("/","-", $from);
                    $from = date("Y-m-d", strtotime($from));
                    $dd = date('D', strtotime($from));
                    if($dd != "Mon"){
                        $monday = "";
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
                    $query->when(($monday && $sunday), function($q) use($monday, $sunday){
                        $q->whereBetween('timesheet_date', [$monday, $sunday]);
                    });

                }

                if($request->query('project_id')){
                    $projectId = $request->project_id;
                    $project = $project = Project::find($projectId);
                    $query->when($projectId, function($q) use($projectId){
                        $q->where('project_id', $projectId);
                    });
                }
                
                if($request->query('user_id')){
                    $userId = $request->user_id;
                    $supervisor = User::find($userId);
                    $query->when($userId, function($q) use($userId){
                        $q->where('supervisor_id', $userId);
                    });
                }

                $timesheets = $query->groupBy('activity_id')
                                    ->orderBy('project_id', 'ASC')
                                    ->orderBy('activity_id', 'ASC')
                                    ->get();
                if($project){
                    $view = view('reportmanager::weekly_staff_timesheet_report_filter_list', compact('project', 'timesheets', 'supervisor', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'))->render();
                }
                
            //}catch(\Exception $e){}
        }
        return response()->json(['html'=> $view]);
    }
    

}
