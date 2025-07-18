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

class ImportActivity implements ToCollection, WithChunkReading, WithHeadingRow
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
        $collections->each(function($row){
            if($row['activity_type'] == 'main_activity'){
                $project = Project::find($this->projectId);
                if($project->mainActivities->isNotEmpty()){
                    $mainCode = ($project->mainActivities->last()->main_code+1);
                }else{
                    $mainCode = 1;
                }
                $this->mainActivity = MainActivity::create(
                    [
                        'project_id' => $this->projectId,
                        'main_code'  => $mainCode,
                        'area'       => $row['area'] ?? '',
                        'level'      => $row['level'] ?? '',
                        'activity'   => $row['activity'] ?? '',
                        'quantity'   => $row['quantity'] ?? 0,
                        'unit_qty'   => $row['unit_quantity'] ?? 0,
                        'unit'       => $row['unit']
                    ]
                );
            }elseif($row['activity_type'] == 'sub_activity'){
                
                if ($this->mainActivity instanceof \Modules\EstimateManager\Entities\MainActivity) {
                    $this->mainActivity = $this->mainActivity->fresh();
                    if($this->mainActivity->subActivities->isNotEmpty()){
                        $subCodes = explode('.', $this->mainActivity->subActivities->last()->sub_code);
                        $subCode = (end($subCodes)+1);
                    }else{
                        $subCode = 1;
                    }
                    $this->subActivity = SubActivity::create(
                        [
                            'main_activity_id'  => $this->mainActivity->id,
                            'sub_code'          => $this->mainActivity->main_code.'.'.$subCode,
                            'activity'          => $row['activity'] ?? '',
                            'quantity'          => $row['quantity'] ?? 0,
                            'unit'              => $row['unit'] ?? ''
                        ]
                    );
                }
            }elseif($row['activity_type'] == 'activity'){
                if ($this->subActivity instanceof \Modules\EstimateManager\Entities\SubActivity) {
                    $this->subActivity = $this->subActivity->fresh();
                    if($this->subActivity->activities->isNotEmpty()){
                        $itemCodes = explode('.', $this->subActivity->activities->last()->item_code);
                        $itemCode = (end($itemCodes)+1);
                    }else{
                        $itemCode = 1;
                    }
                    $activity = Activity::create(
                        [
                            'sub_activity_id' => $this->subActivity->id,
                            'item_code'       => $this->subActivity->sub_code.'.'.$itemCode,
                            'activity'        => $row['activity'] ?? '',
                            'level'           => $row['level'] ?? '',
                            'quantity'        => $row['quantity'] ?? 0,
                            'rate'            => $row['rate'] ?? 0,
                            'selling_cost'    => $row['rate'] ?? 0,
                            'total'           => (($row['quantity'] ?? 0)*($row['rate'] ?? 0)),
                            'profit'          => (($row['rate'] ?? 0)-($row['rate'] ?? 0)),
                            'unit'            => $row['unit'] ?? ''
                        ]
                    );
                }
            }else{
               Log::info('Invalid activity type:'. $row['activity_type']);
            }
        });

        $project = Project::findOrFail($this->projectId);

        $base_margin = $project->base_margin ?? 0;
        $base_labour = $project->hr_rate ?? 0;

        $project_total = 0;
        $thr_main = 0;
        $tmhr_main = 0;
        $base_margin = $project->base_margin;
        $base_labour = $project->hr_rate;

        if($project->mainActivities->isNotEmpty()){
            foreach($project->mainActivities as $mainActivity){
                $sub_activity_total = 0;
                $thr_sub = 0;
                $tmhr_sub = 0;
                if($mainActivity->subActivities->isNotEmpty()){
                    foreach($mainActivity->subActivities as $subActivity){
                        $activity_total = 0;
                        $thr = 0;
                        $tmhr = 0;
                        if($subActivity->activities->isNotEmpty()){
                            foreach($subActivity->activities as $activity){

                                $rate = $activity->rate;
                                $unit_trim = preg_replace('/\s+/', '', $activity->unit);

                                if($unit_trim == "hr"){
                                    $rate = $base_labour;
                                }
                                $selling_cost = $rate/(1-($base_margin/100));
                                if($project->formulas->isNotEmpty()){
                                    foreach($project->formulas as $formula){
                                        $str = $formula->formula;
                                        $pt1 = "/x/i";
                                        $str = preg_replace($pt1, "*", $str);
                                        $pt2 = "/([a-z])+/i";
                                        $str = preg_replace($pt2, "\$$0", $str);
                                        $pt3 = "/([0-9])+%/";
                                        $str = preg_replace($pt3, "($0/100)", $str);
                                        $pt4 = "/%/";
                                        $str = preg_replace($pt4, "", $str);
                                        if($unit_trim == $formula->keyword){
                                            $rate = $base_labour;
                                            $comm = $rate;
                                            $e = "\$comm = $str;";
                                            eval($e);  
                                            $rate = $comm; 
                                            $selling_cost = $comm/(1-($base_margin/100));
                                        }
                                    }
                                }
                                $total = ($selling_cost * $activity->quantity);
                                $activity_total = $activity_total + $total;

                                if($unit_trim == "hr"){
                                    $thr = $thr + $total;	
                                }
                                if($unit_trim == "mhr"){
                                    $tmhr = $tmhr + $total;	 
                                }
                                $activity->activity = $activity->activity;
                                $activity->unit = $activity->unit;
                                $activity->quantity = $activity->quantity;
                                $activity->rate = $rate;
                                $activity->selling_cost = $selling_cost;
                                $activity->total = $total;
                                // save activity
                                $activity->save();
                            }
                        }
                        $rate = $activity_total;
                        $total = $rate * $subActivity->quantity;
                        $sub_activity_total = $sub_activity_total + $total;
                        
                        $hr = $subActivity->hr;
                        $total_hr = $subActivity->total_hr;
                        $mhr = $subActivity->mhr;
                        $total_mhr = $subActivity->total_mhr;
                        
                        $hr = $thr;
                        $mhr = $tmhr;

                        $total_hr = $hr * $subActivity->quantity;
                        $total_mhr = $mhr * $subActivity->quantity;
                        
                        $thr_sub = $thr_sub + $total_hr;
                        $tmhr_sub = $tmhr_sub + $total_mhr;
                        $subActivity->activity = $subActivity->activity;
                        $subActivity->quantity = $subActivity->quantity;
                        $subActivity->rate = $rate;
                        $subActivity->total = $total;
                        $subActivity->hr = $hr;
                        $subActivity->mhr = $mhr;
                        $subActivity->total_hr = $total_hr;
                        $subActivity->total_mhr = $total_mhr;
                        // save sub activity
                        $subActivity->save();
                    }
                }
                $rate = $sub_activity_total;
                $total = $rate * $mainActivity->quantity;
                $project_total =  $project_total + $total;	

                $hr = $mainActivity->hr;
                $total_hr = $mainActivity->total_hr;
                $mhr = $mainActivity->mhr;
                $total_mhr = $mainActivity->total_mhr;
                                        
                $hr = $thr_sub;
                $mhr = $tmhr_sub;

                $total_hr = $hr * $mainActivity->quantity;
                $total_mhr = $mhr * $mainActivity->quantity;                   
                $thr_main = $thr_main + $total_hr;
                $tmhr_main = $tmhr_main + $total_mhr;
                if(($mainActivity->unit_qty > 0) && ($total > 0)){
                    $unit_rate = ($total/$mainActivity->unit_qty);
                }else{
                    $unit_rate = 0;
                }	
                $mainActivity->area = $mainActivity->area;
                $mainActivity->level = $mainActivity->level;
                $mainActivity->activity = $mainActivity->activity;
                $mainActivity->quantity = $mainActivity->quantity;
                $mainActivity->rate = $rate;
                $mainActivity->total = $total;
                $mainActivity->hr = $hr;
                $mainActivity->mhr = $mhr;
                $mainActivity->total_hr = $total_hr;
                $mainActivity->total_mhr = $total_mhr;
                $mainActivity->unit_qty = $mainActivity->unit_qty;
                $mainActivity->unit_rate = $unit_rate;
                $mainActivity->unit = $mainActivity->unit;
                // save main activity
                $mainActivity->save();
            }
        }
        $project->project_total = $project_total;
        // save project
        $project->save();
        
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
