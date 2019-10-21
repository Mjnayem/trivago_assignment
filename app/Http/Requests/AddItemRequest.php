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
            'name' => 'required|string',
            'rating' => 'required|integer',
            'category' => 'required|string',
            'location_id' => 'required|integer',
            'image_url' => 'required|string',
            'reputation' => 'required|integer',
            'price' => 'required|integer',
            'availability' => 'required|integer',
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
