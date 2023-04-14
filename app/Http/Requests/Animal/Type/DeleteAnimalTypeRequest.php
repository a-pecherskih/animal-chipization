<?php

namespace App\Http\Requests\Animal\Type;

use App\Http\Requests\BaseRequest;

class DeleteAnimalTypeRequest extends BaseRequest
{
    protected function prepareForValidation()
    {
        request()->merge([
            'animalId' => request()->route('animalId'),
            'typeId' => request()->route('typeId'),
        ]);
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
            'typeId' => 'required|numeric|min:1',
        ];
    }
}
