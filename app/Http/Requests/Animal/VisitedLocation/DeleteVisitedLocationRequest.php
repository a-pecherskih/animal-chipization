<?php

namespace App\Http\Requests\Animal\VisitedLocation;

use App\Http\Requests\BaseRequest;

class DeleteVisitedLocationRequest extends BaseRequest
{
    protected function prepareForValidation()
    {
        request()->merge([
            'animalId' => request()->route('animalId'),
            'pointId' => request()->route('pointId'),
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
            'pointId' => 'required|numeric|min:1',
        ];
    }
}
