<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class SearchAccountsRequest extends FormRequest
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
