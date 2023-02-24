<?php

namespace App\Http\Requests\Animal\VisitedLocation;

use App\Exceptions\ModelNotFoundException;
use App\Http\Requests\BaseRequest;

class DeleteVisitedLocationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     * @throws \App\Exceptions\ModelNotFoundException
     */
    public function rules()
    {
        $animal = request()->route('animal');
        $animalLocation = request()->route('animalLocation');

        if (!$animal->visitedLocations->firstWhere('id', $animalLocation->visited_location_id)) {
            throw new ModelNotFoundException();
        }

        return [];
    }
}
