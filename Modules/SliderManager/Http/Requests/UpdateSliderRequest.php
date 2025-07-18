<?php

namespace Modules\SliderManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;

class UpdateSliderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        //dd($request->input());
        //dd($request->segment(3));
        return [
            'title' => 'required|min:3',
            'description'=>'',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status'=>''
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
            'title.required'  => 'Please enter title!',
            'title.min'  => 'Title must be at least 3 characters long!'            
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
