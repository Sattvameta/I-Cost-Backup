<?php

namespace App\Imports;

use Log;
use App\User;
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

class UsersImport implements ToCollection, WithChunkReading, WithHeadingRow
{
    use Importable;

    private $companyId;

    private $categoryId;

    /** New class instance
    * @param int $projectId
    */
    public function __construct($companyId, $categoryId)
    {
        $this->companyId = $companyId;
        $this->categoryId = $categoryId;
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
            $supplier = new User();
            $supplier->company_id = $this->companyId;
            $supplier->category_id = $this->categoryId;
            $supplier->supplier_name = $row['supplier_name'];
            $supplier->supplier_contact_name = $row['supplier_contact_name'];
            $supplier->full_name = $row['account_name'];
            $supplier->email = $row['email'];
            $supplier->phone = $row['phone'];
            $supplier->address_line1 = $row['address_line1'];
            $supplier->address_line2 = $row['address_line2'];
            $supplier->fax = $row['fax'];
            $supplier->postcode = $row['postcode'];
            $supplier->suburb = $row['town'];
            $supplier->save();

            // Attach role with supplier
            $supplier->roles()->attach(3);
        });
        
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
            '*.account_name'         => 'bail|nullable',
            '*.email'                => 'bail|nullable',
            '*.address_line1'        => 'bail|nullable',
            '*.address_line2'        => 'bail|nullable',
            '*.phone'                => 'bail|nullable',
            '*.fax'                  => 'bail|nullable',
            '*.town'                 => 'bail|nullable',
            '*.postcode'             => 'bail|nullable',
            "*.supplier_name"        => 'bail|nullable',
            "*.supplier_contact_name"=> 'bail|nullable',
        ];
    }

    /** Get rules messages
    * @return array  
    */
    public function rulesMessages()
    {
        return [
            '*.account_name.required'           => 'The account name is required.',
            '*.account_name.max'                => 'The account name may not be greater than 50 characters.',
            '*.email.required'                  => 'The email is required.',
            '*.email.email'                     => 'The email must be a valid email address.',
            '*.email.max'                       => 'The email may not be greater than 50 characters.',
            '*.email.unique'                    => 'The email has already been taken.',
            '*.address_line1.max'               => 'The address line1 may not be greater than 100 characters.',
            '*.address_line2.max'               => 'The address line2 may not be greater than 100 characters.',
            '*.phone.required'                  => 'The phone is required.',
            '*.phone.numeric'                   => 'The phone must be nemeric.',
            '*.phone.unique'                    => 'The phone has already been taken.',
            '*.fax.max'                         => 'The fax may not be greater than 50 characters.',
            '*.town.max'                        => 'The town may not be greater than 50 characters.',
            '*.postcode.max'                    => 'The postcode may not be greater than 50 characters.',
            '*.supplier_name.max'               => 'The supplier name may not be greater than 100 characters.',
            '*.supplier_contact_name.max'       => 'The supplier contact name may not be greater than 100 characters.',
        ];
    }

}
