<?php

namespace App\Http\Requests\AnimalType;

use Illuminate\Foundation\Http\FormRequest;

class CreateAnimalTypeRequest extends FormRequest
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
