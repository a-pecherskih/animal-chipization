<?php

namespace App\Http\Requests\Account;

use App\Exceptions\ModelFieldExistsException;
use App\Http\Requests\BaseRequest;

class RegistrationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ];
    }

    protected function checkCustomFails($validator)
    {
        if (isset($validator->failed()['email']['Unique'])) {
            throw new ModelFieldExistsException;
        }
    }
}
