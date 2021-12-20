<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $data  = array(
            'status' => true,
            'code'   => 422,
            'data'   => $validator->errors(),
            'message' => 'Validation failed',
        );
        throw (new ValidationException($validator,response()->json($data,422)));

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'string|required',
            'last_name'  => 'string|required',
            'username'   => 'string|required|users,username',
            'email' => 'string|required|unique:users,email',
            'password' => 'string|required',
        ];
    }

}
