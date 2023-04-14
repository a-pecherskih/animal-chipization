<?php

namespace App\Http\Requests\Animal\VisitedLocation;

use App\Http\Requests\BaseRequest;

class UpdateVisitedLocationPointRequest extends BaseRequest
{
    protected function prepareForValidation()
    {
        request()->merge([
            'animalId' => request()->route('animalId'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'animalId' => 'required|numeric|min:1',
            'visitedLocationPointId' => 'required|numeric|min:1',
            'locationPointId' => 'required|numeric|min:1',
        ];
    }
}
