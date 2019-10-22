<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class AddItemRequest extends FormRequest
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
//            'name' => 'required|string|min:11|regex:/^(?!(free|Offer|Book|Website))*$/',
            'name' => 'required|string|min:11',
//            'hotelier_id' => 'required|integer',
            'rating' => 'required|integer|between:0,5',
            'category' => 'required|string',
//
           'image_url' => 'required|string|regex:/^(http(s)?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'reputation' => 'required|integer|between:0,1000',
            'price' => 'required|integer',
            'availability' => 'required|integer',
            'city' => 'required|string|max:128',
            'state' => 'required|string|max:128',
            'country' => 'required|string|max:128',
            'zip_code' => 'required|integer|min:10000|max:99999',
            'address' => 'required|string|max:128',

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
