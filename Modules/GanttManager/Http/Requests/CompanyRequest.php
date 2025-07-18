<?php

namespace Modules\CompanyManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->id;

        switch ($this->method()) {
            case 'GET':{
                return [];
            }
            case 'DELETE': {
                    return [];
            }
            case 'POST': {
                return [
                    'category_id'       => 'bail|required',
                    'full_name'         => 'bail|required|max:50',
                    'email'             => 'bail|required|email|max:100|unique:users,email',
                    'password'          => 'bail|required|min:6|max:12',
                    'confirm_password'  => 'bail|required|same:password',
                    'address_line1'     => 'bail|nullable|max:100',
                    'address_line2'     => 'bail|nullable|max:100',
                    'phone'             => 'bail|required|numeric|unique:users,phone',
                    'fax'               => 'bail|nullable|max:50',
                    'suburb'            => 'bail|nullable|max:50',
                    'postcode'          => 'bail|nullable|max:50',
                    'file'              =>'bail|nullable|image',
                    'company_name'      => 'bail|required|max:100',
                    'company_contact'   => 'bail|required|numeric|unique:users,company_contact',
                    'company_logo'      =>'bail|nullable|image',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'category_id'       => 'bail|required',
                    'full_name'         => 'bail|required|max:50',
                    'email'             => 'bail|required|email|max:100|unique:users,email,'.$id.',id',
                    'password'          => 'bail|nullable|min:6|max:12',
                    'confirm_password'  => 'bail|nullable|same:password',
                    'address_line1'     => 'bail|nullable|max:100',
                    'address_line2'     => 'bail|nullable|max:100',
                    'phone'             => 'bail|required|numeric|unique:users,phone,'.$id.',id',
                    'fax'               => 'bail|nullable|max:50',
                    'suburb'            => 'bail|nullable|max:50',
                    'postcode'          => 'bail|nullable|max:50',
                    'file'              => 'bail|nullable|image',
                    'company_name'      => 'bail|required|max:100',
                    'company_contact'   => 'bail|required|numeric|unique:users,company_contact,'.$id.',id',
                    'company_logo'      =>'bail|nullable|image',
                ];
            }
            default:break;
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if($this->hasFile('file')){
                if ($this->file('file')->getSize() > '5242880') {
                    $validator->errors()->add('file', 'Avatar shouldn\'t be greater than 5 MB. Please select another file.');
                }
            }
            if($this->hasFile('logo')){
                if ($this->file('logo')->getSize() > '5242880') {
                    $validator->errors()->add('logo', 'Logo shouldn\'t be greater than 5 MB. Please select another file.');
                }
            }
        });
    }
    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'category_id' => 'category',
            'suburb' => 'town',
            'logo' => 'company logo',
            'file' => 'avatar'
        ];
    }
}
