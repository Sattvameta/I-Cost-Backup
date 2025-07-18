<?php

namespace Modules\FormulaManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;

class GeneralFormulaRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request) {
         switch ($this->method()) {
            case 'POST':
                return [
                    'project_id'    => 'bail|required',
                    'keyword'       => 'bail|required|max:150',
                    'description'   => 'bail|required|max:150',
                    'formula'       => 'bail|required|max:150',          
                    'value'         => 'bail|required|numeric', 
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'project_id'    => 'bail|required',
                    'keyword'       => 'bail|required|max:150',
                    'description'   => 'bail|required|max:150',
                    'formula'       =>'bail|required|max:150',          
                    'value'         =>'bail|required|numeric', 
                ];
            default:break;
        }
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return [
            'project_id.required' => 'The project field is required.'
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
