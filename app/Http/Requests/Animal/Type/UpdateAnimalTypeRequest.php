<?php

namespace App\Http\Requests\Animal\Type;

use App\Exceptions\ModelNotFoundException;
use App\Http\Requests\BaseRequest;
use App\Rules\Animal\Type\TypeAlreadyExistRule;
use App\Rules\Animal\Type\TypeNotExistRule;

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
            'oldTypeId' => ['required', 'gt:0', 'bail', 'exists:animal_types,id', new TypeNotExistRule()],
            'newTypeId' => ['required', 'gt:0', 'bail', 'exists:animal_types,id', new TypeAlreadyExistRule()],
        ];
    }

    protected function checkCustomFails($validator)
    {
        if (isset($validator->failed()['oldTypeId']['Exists'])
            || isset($validator->failed()['newTypeId']['Exists'])
        ) {
            throw new ModelNotFoundException();
        }
    }
}
