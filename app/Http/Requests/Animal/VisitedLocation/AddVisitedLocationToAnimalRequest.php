<?php

namespace App\Http\Requests\Animal\VisitedLocation;

use App\Exceptions\BadRequestException;
use App\Http\Requests\BaseRequest;
use App\Models\Animal;

class AddVisitedLocationToAnimalRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     * @throws \App\Exceptions\BadRequestException
     */
    public function rules()
    {
        $animal = request()->route('animal');
        $location = request()->route('location');

        if (($animal->life_status === Animal::STATUS_DEAD)
            || $this->animalHasOnlyChippingLocation($animal, $location)
            || $this->animalAlreadyInThisPoint($animal, $location)
            || (!count($animal->visitedLocations) && $location->id == $animal->chipping_location_id)
        ) {
            throw new BadRequestException();
        }

        return [];
    }

    private function animalAlreadyInThisPoint($animal, $location)
    {
        if (!count($animal->visitedLocations)) return false;

        return $animal->visitedLocations->last()->id == $location->id;
    }

    private function animalHasOnlyChippingLocation($animal, $location)
    {
        return (count($animal->visitedLocations) == 1
            && ($animal->chipping_location_id == $location->id)
            && ($location->id == $animal->visitedLocations[0]->id));
    }
}
