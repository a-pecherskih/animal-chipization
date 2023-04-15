<?php

namespace App\Http\Requests\AnimalType;

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
            'type' => 'required|string',
        ];
    }
}
