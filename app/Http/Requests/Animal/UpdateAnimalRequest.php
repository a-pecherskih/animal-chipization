<?php

namespace App\Http\Requests\Animal;

use App\Exceptions\ModelFieldExistsException;
use App\Http\Requests\BaseRequest;
use App\Models\Animal;
use App\Rules\Animal\ChangeChippingLocationRule;
use App\Rules\Animal\ChangeLifeStatusRule;

class UpdateAnimalRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'weight' => 'required|numeric|gt:0',
            'length' => 'required|numeric|gt:0',
            'height' => 'required|numeric|gt:0',
            'gender' => 'required|in:' . implode(',', Animal::getGendersList()),
            'lifeStatus' => ['required', 'in:' . implode(',', Animal::getStatusesList()), new ChangeLifeStatusRule()],
            'chipperId' => 'required|gt:0|bail|exists:users,id',
            'chippingLocationId' => ['required', 'gt:0', 'bail', 'exists:locations,id', new ChangeChippingLocationRule()],
        ];
    }
}
