<?php

namespace App\Http\Requests\Animal\VisitedLocation;

use App\Http\Requests\BaseRequest;

class SearchVisitedLocationsRequest extends BaseRequest
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
