<?php

namespace App\Http\Requests\Animal;

use App\Exceptions\ModelFieldExistsException;
use App\Exceptions\ModelNotFoundException;
use App\Http\Requests\BaseRequest;
use App\Models\Animal;

class CreateAnimalRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'animalTypes' => 'required|array',
            'animalTypes.*' => 'required|distinct|exists:animal_types,id',
            'weight' => 'required|numeric|gt:0',
            'length' => 'required|numeric|gt:0',
            'height' => 'required|numeric|gt:0',
            'gender' => 'required|in:' . implode(',', Animal::getGendersList()),
            'chipperId' => 'required|exists:users,id',
            'chippingLocationId' => 'required|exists:locations,id',
        ];
    }

    protected function checkCustomFails($validator)
    {
        if (isset($validator->failed()['animalTypes.0']['Distinct'])) {
            throw new ModelFieldExistsException();
        }
        if (isset($validator->failed()['animalTypes.0']['Exists'])
            || isset($validator->failed()['chipperId']['Exists'])
            || isset($validator->failed()['chippingLocationId']['Exists'])
        ) {
            throw new ModelNotFoundException;
        }
    }
}
