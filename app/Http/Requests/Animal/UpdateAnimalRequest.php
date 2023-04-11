<?php

namespace App\Http\Requests\Animal;

use App\Exceptions\ModelNotFoundException;
use App\Http\Requests\BaseRequest;
use App\Models\Animal;
use App\Rules\Animal\ChangeChippingLocationRule;
use App\Rules\Animal\ChangeLifeStatusRule;

class UpdateAnimalRequest extends BaseRequest
{
    protected function prepareForValidation()
    {
        request()->merge(['id' => request()->route('id')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => 'required|numeric|min:1',
            'weight' => 'required|numeric|gt:0',
            'length' => 'required|numeric|gt:0',
            'height' => 'required|numeric|gt:0',
            'gender' => 'required|in:' . implode(',', Animal::getGendersList()),
            'lifeStatus' => ['required', 'in:' . implode(',', Animal::getStatusesList()), new ChangeLifeStatusRule()],
            'chipperId' => 'required|gt:0|bail|exists:users,id',
            'chippingLocationId' => ['required', 'gt:0', 'bail', 'exists:locations,id', new ChangeChippingLocationRule()],
        ];
    }

    protected function afterValidation($validator)
    {
        if (isset($validator->failed()['chipperId']['Exists'])
            || isset($validator->failed()['chippingLocationId']['Exists'])
        ) {
            throw new ModelNotFoundException();
        }
    }
}
