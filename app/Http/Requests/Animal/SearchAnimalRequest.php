<?php

namespace App\Http\Requests\Animal;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Animal;

class SearchAnimalRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'startDateTime' => 'date|nullable',
            'endDateTime' => 'date|nullable',
            'chipperId' => 'numeric|min:1',
            'chippingLocationId' => 'numeric|min:1',
            'lifeStatus' => 'nullable|in:' . implode(',', Animal::getStatusesList()),
            'gender' => 'nullable|in:' . implode(',', Animal::getGendersList()),
            'from' => 'nullable|integer|min:0',
            'size' => 'nullable|integer|min:1',
        ];
    }
}
