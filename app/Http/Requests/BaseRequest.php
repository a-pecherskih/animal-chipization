<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class BaseRequest extends FormRequest
{
    protected function checkCustomFails($validator) {}

    public function failedValidation(Validator $validator)
    {
        $this->checkCustomFails($validator);

        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], Response::HTTP_BAD_REQUEST));
    }
}
