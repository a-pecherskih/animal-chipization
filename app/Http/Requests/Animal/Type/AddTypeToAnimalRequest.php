<?php

namespace App\Http\Requests\Animal\Type;

use Illuminate\Foundation\Http\FormRequest;

class AddTypeToAnimalRequest extends FormRequest
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
