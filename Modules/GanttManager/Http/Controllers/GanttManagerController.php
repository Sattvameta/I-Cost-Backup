<?php

namespace Modules\GanttManager\Http\Controllers;

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
use App\Imports\ImportGanttActivity;


class GanttManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        
        
        
        /*if (!auth()->user()->can('access', 'admins visible') && (!auth()->user()->isRole('Super Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }*/
       
        $categories = Category::where('status', 1)
                ->pluck('name', 'id');
        $categories->prepend('All', '');
        
        
        $query = Project::whereStatus(1);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
                $query->where('company_id', auth()->user()->company_id);
            }
        }
        $projects = $query->get(['id', 'project_title', 'version']);
        $projects = $projects->pluck('display_project_title', 'id');
        
        

        return view('ganttmanager::index', compact('categories','projects'));
    }
    
    public function get($id){
        $tasks = new Task();
        $links = new Link();
        $test = \DB::table('tasks')
                     ->select('*')
                     ->where('project_id', $id)
                     ->orderBy('id', 'ASC')
                     ->get()->toArray();
        
        //print_r($test);exit;
        $live_task = \DB::table('main_activities')->select('id AS recordid','activity AS text','start_date','progress','duration','project_id')->where('project_id',33)->get()->toArray();      
        if(count($live_task) > 0){
            foreach($live_task as $k=>$ids){
                $live_task[$k]->parent="0";
                $live_task[$k]->id=$ids->recordid;
                $live_task[$k]->open=true;
                $live_task[$k]->type=null;
                $live_task[$k]->readonly=null;
                $live_task[$k]->editable=null;
                $live_task[$k]->type_of_act='main_activity';
                
              
                $live_tasks = \DB::table('sub_activities')->select('id AS recordid','activity AS text','start_date','progress','duration','main_activity_id AS parent')->where('main_activity_id',$ids->recordid)->get()->toArray();
                
                if(count($live_tasks) > 0){
                   $i=0;
                   foreach($live_tasks as $idk=>$liv){
                        $i++;
                        $live_tasks[$idk]->id=$ids->recordid.$i;
                        $live_tasks[$idk]->open=true;
                        $live_tasks[$idk]->type=null;
                        $live_tasks[$idk]->readonly=null;
                        $live_tasks[$idk]->editable=null;
                        $live_tasks[$idk]->type_of_act='sub_activity';
                         array_push($live_task, $liv);
                         
                         
                         $live_tasks_act = \DB::table('activities')->select('id AS recordid','activity AS text','start_date','progress','duration','sub_activity_id')->where('sub_activity_id',$liv->recordid)->get()->toArray();
                        //return $liv->recordid;
                        if(count($live_tasks_act) > 0){
                           $j=0;
                           foreach($live_tasks_act as $id_act=>$liv_act){
                                $j++;
                                $live_tasks_act[$id_act]->parent=$ids->recordid.$i;
                                $live_tasks_act[$id_act]->id=$ids->recordid.$i.$j;
                                $live_tasks_act[$id_act]->open=true;
                                $live_tasks_act[$id_act]->type=null;
                                $live_tasks_act[$id_act]->readonly=null;
                                $live_tasks_act[$id_act]->editable=null;
                                $live_tasks_act[$id_act]->type_of_act='activity';
                                
                                 array_push($live_task, $liv_act);
                            }
                            
                        }
                    }
                    
                }
                
                
            }
        }

     // return $live_task;
        //$dummy[] =(object) array('id'=>1,'start_date'=>'2020-10-14','text'=>'sample text','progress'=>'3','duration'=>'4','parent'=>'0');
        $dg=(object) $live_task;

//print_r($test);print_r("-----------");


        return response()->json([
        //"data" => $live_task,
        //"data" => $tasks->all(),
         "data" =>   $test,
        "links" => $links->all()
        ]);
    }
    
    public function getrr(){
        
        return 5;
    }
    
    public function updategant_task($id, Request $request){
      
      
      
          
         if((!empty($request->activity_type)) && $request->activity_type == "main_activity" )
         {
            \DB::table('main_activities')->where('id',$request->id)->update(['start_date'=> $request->start_date, "duration" => $request->duration]);
             
         }
         if((!empty($request->activity_type)) && $request->activity_type == "sub_activity" )
         {
            \DB::table('sub_activities')->where('id',$request->id)->update(['start_date'=> $request->start_date, "duration" => $request->duration]);
             
         }
         if((!empty($request->activity_type)) && $request->activity_type == "activity" )
         {
            \DB::table('activities')->where('id',$request->id)->update(['start_date'=> $request->start_date, "duration" => $request->duration]);
             
         }
        return response()->json(["msg"=>"true"]);
        
        /*print_r($id.'----');
        return $request->all();*/
        
    }
    
    /**
     * Display project estimate import view.
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function importProjectGanttView(Request $request){
        if (!auth()->user()->can('access', 'gantt add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $query = Project::whereStatus(1);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
                $query->where('company_id', auth()->user()->company_id);
            }
        }
        $allProjects = $query->get(['id', 'project_title', 'version']);
        $allProjects = $allProjects->pluck('display_project_title', 'id');
        $allProjects->prepend('Select project', '');

        return view('ganttmanager::importGantt', compact('allProjects'));
    }
    /**
     * Display project estimate import view.
     * @param ImportActivityRequest $request
     * @param $id
     * @return Response
     */
    public function importProjectGantt(ImportActivityRequest $request){
        if (!auth()->user()->can('access', 'gantt add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try {
            $path = $request->file('file')->store('temp'); 
            
            $path = storage_path('app').'/'.$path;  
            
            $import = new ImportGanttActivity($request->project_id);
           
            $import->import($path); 
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
        
        
        $query = Project::whereStatus(1);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
                $query->where('company_id', auth()->user()->company_id);
            }
        }
        $allProjects = $query->get(['id', 'project_title', 'version']);
        $allProjects = $allProjects->pluck('display_project_title', 'id');
        $allProjects->prepend('Select project', '');

        return view('ganttmanager::importGantt', compact('allProjects'));

        //return redirect()->route('estimates.projects', $request->project_id)->with("success", "Estimates has been imported successfully!");
    }
    
   

}
