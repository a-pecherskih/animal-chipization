<?php

namespace App\Http\Requests\Animal\Type;

use App\Exceptions\ModelFieldExistsException;
use App\Http\Requests\BaseRequest;

class AddTypeToAnimalRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     * @throws \App\Exceptions\ModelFieldExistsException
     */
    public function rules()
    {
        $animal = request()->route('animal');
        $type = request()->route('animalType');

        if ($animal->types->firstWhere('id', $type->id)) {
            throw new ModelFieldExistsException();
        }

        return [];
    }
}
