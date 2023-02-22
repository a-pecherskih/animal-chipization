<?php

namespace App\Http\Requests\Animal\Type;

use App\Exceptions\BadRequestException;
use App\Exceptions\ModelNotFoundException;
use App\Http\Requests\BaseRequest;

class DeleteAnimalTypeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $animal = request()->route('animal');
        $type = request()->route('animalType');

        if (!$animal->types->firstWhere('id', $type->id)) {
            throw new ModelNotFoundException();
        }

        if (count($animal->types) == 1 && $animal->types->first()->id == $type->id) {
            throw new BadRequestException();
        }

        return [];
    }
}
