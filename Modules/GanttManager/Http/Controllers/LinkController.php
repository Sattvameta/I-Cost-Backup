<?php

namespace Modules\GanttManager\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Link;
use App\Task;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
 
class LinkController extends Controller
{
    public function store(Request $request){
        
        $link = new Link();
 
        $link->type = $request->type;
        $link->source = $request->source;
        $link->target = $request->target;
 
        $link->save();
		  if($request->type == 0){
            $this->update_target_startdate($request->source,$request->target);
        }
 
        return response()->json([
            "action"=> "inserted",
            "tid" => $link->id
        ]);
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
 
    public function update($id, Request $request){
        $link = Link::find($id);
 
        $link->type = $request->type;
        $link->source = $request->source;
        $link->target = $request->target;
 
        $link->save();
 
        return response()->json([
            "action"=> "updated"
        ]);
    }
 
    public function destroy($id){
        $link = Link::find($id);
        $link->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }
}
