<?php

namespace App\Http\Requests\Account;

use App\Http\Requests\BaseRequest;

class SearchAccountsRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'firstName' => 'string|nullable',
            'lastName' => 'string|nullable',
            'email' => 'string|nullable',
            'from' => 'nullable|integer|min:0',
            'size' => 'nullable|integer|min:1',
        ];
    }
}
