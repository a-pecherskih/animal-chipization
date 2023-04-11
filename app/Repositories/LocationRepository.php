<?php

namespace App\Repositories;

use App\Models\Location;

class LocationRepository
{
    public function findById(int $id): Location
    {
        return Location::query()->firstWhere('id', $id);
    }

    public function findByIdOrFail(int $id): Location
    {
        return Location::query()->findOrFail('id', $id);
    }

    public function findByLatAndLon($latitude, $longitude): Location
    {
        return Location::query()
            ->firstWhere([
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]);
    }

    public function create($data): Location
    {
        return Location::query()->create([
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ]);
    }

    public function update(Location $location, $data): Location
    {
        $location->fill([
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ]);
        $location->save();

        return $location;
    }
}
