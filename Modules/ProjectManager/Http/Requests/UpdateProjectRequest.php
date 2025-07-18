<?php

namespace Modules\ProjectManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;

class UpdateProjectRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request) {
        $rules = [
            'company_id' => 'bail|required',
            'unique_reference_no' => 'bail|required|max:150|unique:projects,unique_reference_no,'.$this->id.',id',
            'project_title' => 'bail|required|min:3|max:150',
            'client'=>'bail|nullable|max:150',          
            'client_contacts'=>'bail|nullable|max:150',     
            'project_address'=>'bail|nullable|max:150',
            'region' => 'bail|nullable|max:150',
            'location'=>'bail|nullable|max:150',
            'sector'=>'bail|nullable|max:150',
            'type_of_contract' => 'bail|max:150',
            'shifts' => 'bail|max:150',
            'project_manager'=>'bail|nullable|max:150',
            'site_supervisor'=>'bail|nullable|max:150',
            'current_start_date'=>'bail|required|date_format:Y-m-d',
            'current_completion_date'=>'bail|nullable|date_format:Y-m-d',
            'current_value_of_project'=>'bail|nullable|numeric|digits_between:1,7',
            'base_margin'=>'bail|required|numeric|digits_between:1,7',
            'change_management'=>'bail|nullable|max:150',
            'adjusted_contract_value'=>'bail|nullable|numeric|digits_between:1,7',
            'labour_value'=>'bail|required|numeric|digits_between:1,7',  
            'tender_status'=>'bail|required',

        ];
         return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return [
            'project_title.required' => 'Please enter title!',
            'project_title.min' => 'Title must be at least 3 characters long!',
            'base_margin.required' => 'Please enter Base Margin!',
            'labour_value.required' => 'Please enter Labour Value!'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

}
