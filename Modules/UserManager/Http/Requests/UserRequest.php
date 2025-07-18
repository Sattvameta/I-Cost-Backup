<?php

namespace Modules\UserManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UserRequest extends FormRequest
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
                    'company_id'        => 'bail|required',
                    'role_id'           => 'bail|required',
                    "full_name"         => 'bail|required|max:50',
                    'email'             => 'bail|required|email|max:100|unique:users,email',
                    'password'          => 'bail|required|min:6|max:12',
                    'confirm_password'  => 'bail|required|same:password',
                    'address_line1'     => 'bail|nullable|max:100',
                    'address_line2'     => 'bail|nullable|max:100',
                    'phone'             => 'bail|required|numeric|unique:users,phone',
                    'fax'               => 'bail|nullable|max:50',
                    'suburb'            => 'bail|nullable|max:50',
                    'postcode'          => 'bail|nullable|max:50',
                    'file'              => 'bail|nullable|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'company_id'        => 'bail|required',
                    'role_id'           => 'bail|required',
                    "full_name"         => 'bail|required|max:50',
                    'email'             => 'bail|required|email|max:100|unique:users,email,'.$id.',id',
                    'password'          => 'bail|nullable|min:6|max:12',
                    'confirm_password'  => 'bail|nullable|same:password',
                    'address_line1'     => 'bail|nullable|max:100',
                    'address_line2'     => 'bail|nullable|max:100',
                    'phone'             => 'bail|required|numeric|unique:users,phone,'.$id.',id',
                    'fax'               => 'bail|nullable|max:50',
                    'suburb'            => 'bail|nullable|max:50',
                    'postcode'          => 'bail|nullable|max:50',
                    'file'              => 'bail|nullable|mimes:jpeg,png,jpg,gif,svg|max:2048'
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
        });
    }
    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'suburb' => 'town',
            'file' => 'avatar'
        ];
    }
}
