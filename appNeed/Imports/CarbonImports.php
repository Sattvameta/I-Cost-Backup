<?php

namespace App\Imports;

use Log;
use App\CarbonCalculator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Modules\ProjectManager\Entities\Project;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;



class CarbonImports implements ToCollection,WithHeadingRow
{
	use Importable;
    private $projectId;

 
    /** New class instance
    * @param int $projectId
    */
    public function __construct($projectId)
    {
        $this->projectId = $projectId;
        
    }  
	
			public function collection(Collection $collections)
			{
         $validator = Validator::make(
            $collections->toArray(), 
            $this->rules()
        );

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        $collections->each(function($row){

  
           $supplier = new CarbonCalculator();
			 //$supplier->id = $row['id'];
            $supplier->project_id = $this->projectId;
            $supplier->materials = $row['materials'];
            $supplier->Transport = $row['transport'];
             $supplier->wastage = $row['wastage'];
            $supplier->quantity = $row['quantity'];
			//$supplier->created_at = date();
            $supplier->save();

            
        });
        
    }
 public function rules()
    {
        return [
           
           
        ];
    }
    /** Chunk size
    * @return int chunkSize 
    */
   
    /** Get rules
    * @return array  
    */
  
}
