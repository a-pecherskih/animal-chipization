<?php

namespace App\Http\Requests\Animal;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Animal;

class UpdateAnimalRequest extends FormRequest
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
            'weight' => 'required|numeric|gt:0',
            'length' => 'required|numeric|gt:0',
            'height' => 'required|numeric|gt:0',
            'gender' => 'required|in:' . implode(',', Animal::getGendersList()),
            'lifeStatus' => 'required|in:' . implode(',', Animal::getStatusesList()),
            'chipperId' => 'required|gt:0',
            'chippingLocationId' => 'required|gt:0',
        ];
    }
}
