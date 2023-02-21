<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class BaseRequest extends FormRequest
{
    public function failedValidation(Validator $validator)
    {
        $status = isset($validator->failed()['email']['Unique']) ? Response::HTTP_CONFLICT : Response::HTTP_BAD_REQUEST;

        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], $status));
    }
}
