<?php

namespace App\Http\Requests\Account;

use App\Exceptions\ModelFieldExistsException;
use App\Http\Requests\BaseRequest;

class UpdateAccountRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $this->user->id . ',id',
            'password' => 'required',
        ];
    }

    protected function afterValidation($validator)
    {
        if (isset($validator->failed()['email']['Unique'])) {
            throw new ModelFieldExistsException;
        }
    }
}
