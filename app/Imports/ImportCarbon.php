<?php

namespace App\Imports;

use App\CarbonMainActivity;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Modules\ProjectManager\Entities\Project;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ImportCarbon implements ToCollection,WithHeadingRow
{
	use Importable;
    /**
    * @param Collection $collection
    */
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

  
           $supplier = new CarbonMainActivity();
			$supplier->company_id = auth()->id();
            $supplier->materials = $row['materials'];
            $supplier->factors = $row['factors'];
            $supplier->mass = $row['mass'];
          
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
