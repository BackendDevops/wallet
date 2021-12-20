<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class PromoCodeRequest extends FormRequest
{
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
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
            'start_date' => 'date|date_format:Y-m-d H:i:s|before:end_date|required',
            'end_date'   => 'date|date_format:Y-m-d H:i:s|after:start_date|required',
            'amount'     =>  'integer|required',
            'quota'      =>  'integer|required',
        ];
    }
}
