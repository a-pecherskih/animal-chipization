<?php

namespace App\Services;

use App\Models\Location;
use App\Repositories\LocationRepository;
use App\Validators\LocationValidator;

class LocationService
{
    private LocationRepository $repository;
    private LocationValidator $validator;

    /**
     * LocationService constructor.
     * @param \App\Repositories\LocationRepository $repository
     * @param \App\Validators\LocationValidator $validator
     */
    public function __construct(LocationRepository $repository, LocationValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * @throws \App\Exceptions\ModelFieldExistsException
     */
    public function create(array $data)
    {
        $this->validator->checkNotExistLocationWithPointOrFail($data['latitude'], $data['longitude']);

        return $this->repository->create($data);
    }

    /**
     * @throws \App\Exceptions\ModelFieldExistsException
     * @throws \App\Exceptions\BadRequestException
     */
    public function update(Location $location, array $data)
    {
        $this->validator->checkNotExistLocationWithPointOrFail($data['latitude'], $data['longitude'], $location);
        $this->validator->checkLocationIsNotVisitedOrChippingPointOrFail($location);

        return $this->repository->update($location, $data);
    }

    /**
     * @throws \App\Exceptions\BadRequestException
     */
    public function delete(int $locationId)
    {
        $location = $this->repository->findByIdOrFail($locationId);

        $this->validator->checkLocationIsNotVisitedOrChippingPointOrFail($location);

        $this->repository->delete($location);
    }
}
