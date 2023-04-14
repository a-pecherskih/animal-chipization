<?php

namespace App\Http\Requests\Animal;

use App\Http\Requests\BaseRequest;

class DeleteAnimalRequest extends BaseRequest
{
    protected function prepareForValidation()
    {
        request()->merge(['animalId' => request()->route('animalId')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'animalId' => 'required|numeric|min:1',
        ];
    }
}
