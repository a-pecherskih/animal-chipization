<?php

namespace App\Services;

use App\Exceptions\BadRequestException;
use App\Models\Location;

class LocationService
{
    public function create(array $data)
    {
        return Location::query()->create([
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ]);
    }

    public function update(Location $location, array $data)
    {
        $location->fill([
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ]);
        $location->save();

        return $location;
    }

    public function delete(Location $location)
    {
        if (count($location->visitedAnimals) > 0) {
            throw new BadRequestException();
        }

        $location->delete();
    }
}
