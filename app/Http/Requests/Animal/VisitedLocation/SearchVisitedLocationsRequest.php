<?php

namespace App\Http\Requests\Animal\VisitedLocation;

use Illuminate\Foundation\Http\FormRequest;

class SearchVisitedLocationsRequest extends FormRequest
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
            'from' => 'nullable|integer|min:0',
            'size' => 'nullable|integer|min:1',
        ];
    }
}
