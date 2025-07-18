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


class TaskController extends Controller
{
     public function store(Request $request){
 
        $task = new Task();
 
        $task->text = $request->text;
        $task->start_date = $request->start_date;
        $task->duration = $request->duration;
        $task->progress = $request->has("progress") ? $request->progress : 0;
        $task->parent = $request->parent;
 
        $task->save();
 
        return response()->json([
            "action"=> "inserted",
            "tid" => $task->id
        ]);
    }
 
    public function update($id, Request $request){
        
        /*$task = Task::find($id);
 
        $task->text = $request->text;
        $task->start_date = $request->start_date;
        $task->duration = $request->duration;
        $task->progress = $request->has("progress") ? $request->progress : 0;
        $task->parent = $request->parent;
 
        $task->save();
 
        return response()->json([
            "action"=> "updated"
        ]);*/
        
        $task = Task::find($id);

        if(!empty($task))
        {
            $string=$request->start_date;
            $s=explode("(",$string);


            $task->text = $request->text;
            $td = strtotime(trim($s[0]));
            $task->start_date = date("Y-m-d", $td);

            $task->duration = $request->duration;
            $task->progress = $request->has("progress") ? $request->progress : 0;
            $task->parent = $request->parent;
            if($request->parent == 0){
                 $typee = "project";   
            }else{
                $typee = "task";
            }
            $task->type = $typee;
            //$task->project_id = $request->project;


            $task->save();
            
            
            //check if it has links
            $this->check_link($id);
            
            
            return response()->json([
                "action"=> "updated"
            ]);
        }else{

            $task = new Task();
 
            $task->text = $request->text;
            $string=$request->start_date;
            $s=explode("(",$string);
            $td = strtotime(trim($s[0]));


            $task->start_date = date("Y-m-d", $td);  
            $task->duration = $request->duration;
            $task->progress = $request->has("progress") ? $request->progress : 0;
            $task->parent = $request->parent;
            
            if($request->parent == 0){
                 $typee = "project";   
            }else{
                $typee = "task";
            }
            $task->type = $typee;
            $task->project_id = $request->project;
     
            $task->save();
     
            return response()->json(["parent"=> $request->parent,"action"=> "inserted","tid" => $task->id]);
            //return response()->json($request->parent);

        }
        
    }
 
    public function check_link($target){
        
        $target_task_link = \DB::table('links')
                     ->select('*')
                     ->where('target', $target)
                     //->where('type', 0)
                     ->get();
                $find_max_date = array();
                 foreach($target_task_link as $val){

                    $st = \DB::table('tasks')
                     ->select('start_date','duration','id')
                     ->where('id', $val->source)
                     //->where('type', 0)
                     ->first();
                    $dur = $st->duration;
                    $t_Date1 = $st->start_date;
                    $t_Date2 = date('Y-m-d', strtotime($t_Date1 . " + ".$dur." day"));
                    array_push($find_max_date,$t_Date2);

                 }
                 //print_r($find_max_date);
                 //print_r(max($find_max_date));
                 if(!empty($find_max_date)){
                     $target_task1 = Task::find($target);
                     $target_task1->start_date = max($find_max_date);
                     $target_task1->save();

                }

                $link_test = \DB::table('links')
                     ->select('*')
                     ->where('source', $target)
                     ->where('type', 0)
                     ->get();
                foreach($link_test as $val2){
      
                    //if(!empty($target_task_link)){
                        //$this->update_target_startdate($link_test->source,$link_test->target);
                        $this->update_target_startdate($val2->source,$val2->target);
                    //}
                }

    }


    public function update_target_startdate($source, $target){
 
        $source_task = Task::find($source);
        if(!empty($source_task))
        {
            $Date1 = $source_task->start_date;
            $Date2 = date('Y-m-d', strtotime($Date1 . " + ".$source_task->duration." day"));
            $target_task = Task::find($target);

           
            if(!empty($target_task))
            {
                

                $target_task->start_date = $Date2;
                $target_task->save();
               

             
                

                $this->check_link($target_task->id);
                
            }
        }
    }
    
    
    public function destroy($id){
        $task = Task::find($id);
        $task->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }

}
