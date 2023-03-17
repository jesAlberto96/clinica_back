<?php

namespace App\Http\Requests;

use App\Http\Controllers\Api\ResponseController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required',
            'device' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'El formato del correo no es valido',
            'password.required' => 'La contraseña es obligatoria',
            'device.required' => 'El tipo de dispositivo es obligatorio',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->response->sendError($validator->errors(), 406));
    }
}
