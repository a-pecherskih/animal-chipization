<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class CreateLocationRequest extends FormRequest
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
            'longitude' => 'required|numeric|between:-180,180',
        ];
    }
}
