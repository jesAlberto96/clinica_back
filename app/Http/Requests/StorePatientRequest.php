<?php

namespace App\Http\Requests;

use App\Http\Controllers\Api\ResponseController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class StorePatientRequest extends FormRequest
{
    
    public function __construct(){
        $this->response = new ResponseController;
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'birth_date' => 'required|date',
            'gender' => 'required',
            'height' => 'required',
            'weight' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name is required.',
            'birth_date.required' => 'The birthday is required.',
            'birth_date.date' => 'The format birthday is incorrect.',
            'gender.required' => 'The gender is required.',
            'height.required' => 'The height is required.',
            'weight.required' => 'The weight is required.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->response->sendError($validator->errors(), 406));
    }
}
