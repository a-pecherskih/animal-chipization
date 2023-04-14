<?php

namespace App\Http\Requests\Animal\Type;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnimalTypeRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        request()->merge([
            'animalId' => request()->route('animalId'),
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
            'oldTypeId' => 'required|numeric|min:1',
            'newTypeId' => 'required|numeric|min:1',
        ];
    }
}
