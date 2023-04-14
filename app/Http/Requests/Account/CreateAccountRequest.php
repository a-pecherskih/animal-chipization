<?php

namespace App\Http\Requests\Account;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class CreateAccountRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|string|in:' . implode(',', Role::getRoles()),
        ];
    }
}
