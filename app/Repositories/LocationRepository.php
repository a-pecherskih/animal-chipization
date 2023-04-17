<?php

namespace App\Repositories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;

class LocationRepository
{
    public function findById(int $id): ?Location
    {
        return Location::query()->firstWhere('id', $id);
    }

    public function findByIdOrFail(int $id): Location
    {
        return Location::query()->findOrFail($id);
    }

    public function findByLatAndLon($latitude, $longitude): ?Location
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

    public function search($data): Collection
    {
        $latitude = $data['latitude'] ?? null;
        $longitude = $data['longitude'] ?? null;

        return Location::query()
            ->when(!blank($latitude), function ($q) use ($latitude) {
                $q->where('latitude', $latitude);
            })
            ->when(!blank($longitude), function ($q) use ($longitude) {
                $q->where('longitude', $longitude);
            })
            ->get();
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

    public function delete(Location $location)
    {
        $location->delete();
    }
}
