<?php

namespace App\Http\Requests\Animal;

use App\Exceptions\ModelFieldExistsException;
use App\Exceptions\ModelNotFoundException;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Animal;

class CreateAnimalRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'animalTypes' => 'required|array|min:1',
            'animalTypes.*' => 'required|distinct|gt:0',
            'weight' => 'required|numeric|gt:0',
            'length' => 'required|numeric|gt:0',
            'height' => 'required|numeric|gt:0',
            'gender' => 'required|in:' . implode(',', Animal::getGendersList()),
            'chipperId' => 'required|gt:0',
            'chippingLocationId' => 'required|gt:0',
        ];
    }
}
