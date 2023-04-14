<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Role;

class UpdateAccountRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        request()->merge(['id' => request()->route('id')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => 'required|numeric|min:1',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|string|in:' . implode(',', Role::getRoles()),
        ];
    }
}
