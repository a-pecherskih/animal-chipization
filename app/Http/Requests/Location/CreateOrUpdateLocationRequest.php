<?php

namespace App\Http\Requests\Location;

use App\Http\Requests\BaseRequest;
use App\Rules\Location\CheckPointExistsRule;

class CreateOrUpdateLocationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => ['required', 'numeric', 'between:-180,180', new CheckPointExistsRule()],
        ];
    }
}
