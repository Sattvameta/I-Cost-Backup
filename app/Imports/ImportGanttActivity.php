<?php

namespace App\Imports;

use Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Modules\ProjectManager\Entities\Project;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\EstimateManager\Entities\Activity;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Modules\EstimateManager\Entities\SubActivity;
use Modules\EstimateManager\Entities\MainActivity;

class ImportGanttActivity implements ToCollection, WithChunkReading, WithHeadingRow
{
    use Importable;

    private $projectId;

    private $mainActivity;

    private $subActivity;

    /** New class instance
    * @param int $projectId
    */
    public function __construct($projectId)
    {
        $this->projectId = $projectId;
        $this->mainActivity = "";
        $this->subActivity = "";
    }

    /** Process collection
    * @param Collection $collection
    */
    public function collection(Collection $collections)
    {
        $validator = Validator::make(
            $collections->toArray(), 
            $this->rules(), 
            $this->rulesMessages()
        );

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        
        $parent_main=$parent_sub=$parent_act=0;
        
       
        foreach($collections as $row){
            //$td = strtotime($row['start_date']);
            //$start = date("Y-m-d", $td);
            
            $excel_date = $row['start_date']; //here is that value 41621 or 41631
            $unix_date = ($excel_date - 25569) * 86400;
            $excel_date = 25569 + ($unix_date / 86400);
            $unix_date = ($excel_date - 25569) * 86400;
            $_date = gmdate("Y-m-d", $unix_date);
            
           

            if($row['activity_type'] == 'main_activity'){
                
                $main_activity = \DB::table('tasks')->insertGetId(['project_id' => $this->projectId,'text'=>$row['activity'],'start_date'=>$_date,'duration'=>1,'progress'=>0,'parent'=>0,'type'=>'project']);
                $parent_main = $main_activity;
                
            }elseif($row['activity_type'] == 'sub_activity'){
               
                $sub_activity = \DB::table('tasks')->insertGetId(['project_id' => $this->projectId, 'text'=>$row['activity'],'start_date'=>$_date,'duration'=>$row['duration'],'progress'=>0,'parent'=> $parent_main,'type'=>'task']);
                $parent_sub = $sub_activity;
                
            }elseif($row['activity_type'] == 'activity'){
                
                $activity = \DB::table('tasks')->insertGetId(['project_id' => $this->projectId, 'text'=>$row['activity'],'start_date'=>$_date,'duration'=>$row['duration'],'progress'=>0,'parent'=>$parent_sub,'type'=>'task']);
               
            }else{
                
               Log::info('Invalid activity type:'. $row['activity_type']);
               
            } 
        }
        
        /*$collections->each(function($row){
           
        });*/

  
        
    }

    /** Chunk size
    * @return int chunkSize 
    */
    public function chunkSize(): int
    {
        return 1000;
    }

    /** Get rules
    * @return array  
    */
    public function rules()
    {
        return [
            '*.activity_type' => 'bail|required|max:100',
            '*.activity'      => 'bail|required|max:100',
            '*.area'          => 'bail|nullable|max:100',
            '*.level'         => 'bail|nullable|max:100',
            '*.quantity'      => 'bail|nullable|numeric',
            '*.rate'          => 'bail|nullable|numeric',
            '*.unit_quantity' => 'bail|nullable|numeric',
            '*.unit'          => 'bail|nullable|max:100'
        ];
    }

    /** Get rules messages
    * @return array  
    */
    public function rulesMessages()
    {
        return [
            '*.activity_type'         => 'The activity type is required.',
            '*.activity'              => 'The activity is required.',
            '*.area.max'              => 'The activity may not be greater than 100 characters.',
            '*.area.max'              => 'The area may not be greater than 100 characters.',
            '*.level.max'             => 'The level may not be greater than 100 characters.',
            '*.quantity.numeric'      => 'The quantity must be a number.',
            '*.rate.numeric'          => 'The rate must be a number.',
            '*.unit_quantity.numeric' => 'The unit quantity must be a number.',
            '*.unit.max'              => 'The unit may not be greater than 100 characters.'
        ];
    }
}
