<?php

namespace App\Validators;

use App\Exceptions\BadRequestException;
use App\Exceptions\ModelFieldExistsException;
use App\Models\Animal;
use App\Models\Location;
use App\Repositories\LocationRepository;

class LocationValidator
{
    private LocationRepository $repository;

    /**
     * LocationValidator constructor.
     * @param \App\Repositories\LocationRepository $repository
     */
    public function __construct(LocationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function checkNotExistLocationWithPointOrFail($latitude, $longitude, ?Location $ignoreLocation = null)
    {
        $location = $this->repository->findByLatAndLon($latitude, $longitude);

        if ($location && $ignoreLocation && ($location->id == $ignoreLocation)) return true;

        if ($location) {
            throw new ModelFieldExistsException();
        }
    }

    public function checkLocationIsNotVisitedOrChippingPointOrFail(Location $location)
    {
        $exist = Animal::query()
            ->where('chipping_location_id', $location->id)
            ->orWhereHas('visitedLocations', function ($q) use ($location) {
                $q->where('visited_location_id', $location->id);
            })->exists();

        if ($exist) {
            throw new BadRequestException();
        }
    }
}
