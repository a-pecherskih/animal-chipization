<?php

namespace App\Http\Requests\AnimalType;

use Illuminate\Foundation\Http\FormRequest;

class ShowAnimalTypeRequest extends FormRequest
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
        ];
    }
}
