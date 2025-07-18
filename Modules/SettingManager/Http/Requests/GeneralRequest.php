<?php

namespace Modules\SettingManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;

class GeneralRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'title' => 'required',
            'slug' => 'unique:settings,id,' . $request->segment(3),
            'config_value' => 'required',
        ];
    }

     /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Please enter setting title!',
            //'slug.required' => 'Please enter setting unique Constant/slug!',
            'slug.unique' => 'Each setting Constant/slug must have a unique Constant/slug! this Constant already added!!',
            'config_value.required'  => 'Please enter setting value!',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
