<?php

namespace App\Http\Requests\AnimalType;

use App\Exceptions\ModelFieldExistsException;
use App\Http\Requests\BaseRequest;

class UpdateAnimalTypeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => 'required|string|unique:animal_types,type,' . $this->animalType->id . ',id',
        ];
    }

    protected function checkCustomFails($validator)
    {
        if (isset($validator->failed()['type']['Unique'])) {
            throw new ModelFieldExistsException;
        }
    }
}