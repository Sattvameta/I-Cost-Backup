<?php

namespace Modules\SettingManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //dd($this->input('setting'));
      // dd($this->setting[0]['id']);
        $rules = [];
            $rules['setting.*.title'] = 'required';
            $rules['setting.*.slug'] = 'required|distinct|unique:settings,slug,[setting.*.id]';
            $rules['setting.*.field_type'] = 'required';
            $rules['setting.*.config_value'] = 'required';
        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'setting.*.title.required' => 'Please enter setting title!',
            'setting.*.slug.required'  => 'Please enter setting slug!',
            'setting.*.slug.distinct'  => 'The Slug (:input) field has a duplicate value!',
            'setting.*.slug.unique'  => 'The Slug (:input) has already been taken!',
            'setting.*.config_value.required' => 'Please upload logo/fav icon image!',
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
