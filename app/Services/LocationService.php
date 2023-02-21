<?php

namespace App\Services;

use App\Models\Location;
use Symfony\Component\HttpFoundation\Response;

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
        if (count($location->animals) > 0) {
            abort(Response::HTTP_BAD_REQUEST);
        }

        $location->delete();
    }
}
