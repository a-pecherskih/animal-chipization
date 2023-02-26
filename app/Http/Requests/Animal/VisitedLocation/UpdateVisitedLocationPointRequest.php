<?php

namespace App\Http\Requests\Animal\VisitedLocation;

use App\Exceptions\ModelNotFoundException;
use App\Http\Requests\BaseRequest;
use App\Rules\Animal\VisitedLocation\CheckNewVisitedPoint;

class UpdateVisitedLocationPointRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'visitedLocationPointId' => ['required', 'bail', 'gt:0', 'exists:animal_locations,id,animal_id,' . $this->route('animal')->id],
            'locationPointId' => ['required', 'bail', 'gt:0', 'exists:locations,id', new CheckNewVisitedPoint()],
        ];
    }

    protected function checkCustomFails($validator)
    {
        if (isset($validator->failed()['visitedLocationPointId']['Exists'])
            || isset($validator->failed()['locationPointId']['Exists'])
        ) {
            throw new ModelNotFoundException();
        }
    }
}
