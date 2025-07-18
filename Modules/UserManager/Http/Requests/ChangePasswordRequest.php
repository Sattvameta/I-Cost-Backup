<?php

namespace Modules\UserManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
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
        return [
            'old_password'     => 'required|min:6|max:12',
            'new_password'     => 'required|min:6|max:12',
            'confirm_password' => 'required|same:new_password',
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
            if(strcmp($this->old_password, $this->new_password) == 0){
                $validator->errors()->add('new_password', 'New Password cannot be same as your old password. Please choose a different password.');
            }
            $user = \Auth::user();
            if(!Hash::check($this->old_password, $user->password)){
                $validator->errors()->add('old_password', 'The old password is incorrect.');
            }
        });
    }

}
