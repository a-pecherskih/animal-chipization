<?php

namespace App\Http\Requests\Location;

use App\Http\Requests\BaseRequest;

class DeleteLocationRequest extends BaseRequest
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
        ];
    }
}