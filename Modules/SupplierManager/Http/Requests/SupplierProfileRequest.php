<?php

namespace Modules\SupplierManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class SupplierProfileRequest extends FormRequest
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

        return [
            "full_name"         => 'bail|required|max:50',
            'email'             => 'bail|required|email|max:100|unique:users,email,'.$id.',id',
            'address_line1'     => 'bail|nullable|max:100',
            'address_line2'     => 'bail|nullable|max:100',
            'phone'             => 'bail|required|numeric|unique:users,phone,'.$id.',id',
            'fax'               => 'bail|nullable|max:50',
            'suburb'            => 'bail|nullable|max:50',
            'postcode'          => 'bail|nullable|max:50',
            'file'              => 'bail|nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            "supplier_name"     => 'bail|required|max:100',
            "supplier_contact_name"  => 'bail|required|max:100',
        ];
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
            'full_name' => 'account name',
            'suburb' => 'town',
            'file' => 'avatar'
        ];
    }
}
