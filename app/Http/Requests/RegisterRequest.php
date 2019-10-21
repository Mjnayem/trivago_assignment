<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:128',
            'email' => 'required|email',
            'password' => 'required|string|max:255|confirmed',
            'password_confirmation' => 'required|string|max:255',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [

            'name.required' => 'Name is required !',
            'email.required' => 'Email is required !',
            'password.required' => 'Password is required !',
            'password_confirmation.required' => 'Confirm Password is required !',

        ];
    }


    /**
     * @var null
     */
    public $validator = null;

    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $this->validator = $validator;
    }



}
