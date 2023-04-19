<?php

namespace App\Services;

use App\Helpers\Hash;
use App\Models\Location;
use App\Packages\Geometry\Geometry;
use App\Repositories\LocationRepository;
use App\Validators\LocationValidator;

class LocationService
{
    private LocationRepository $repository;
    private LocationValidator $validator;
    private Geometry $geometry;

    /**
     * LocationService constructor.
     * @param \App\Repositories\LocationRepository $repository
     * @param \App\Validators\LocationValidator $validator
     * @param \App\Packages\Geometry\Geometry $geometry
     */
    public function __construct(LocationRepository $repository, LocationValidator $validator, Geometry $geometry)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->geometry = $geometry;
    }


    /**
     * @throws \App\Exceptions\ModelFieldExistsException
     */
    public function show(int $locationId)
    {
        return $this->repository->findByIdOrFail($locationId);
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
    public function update(int $id, array $data)
    {
        $location = $this->repository->findByIdOrFail($id);

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

    public function findByCoordinates(array $data): Location
    {
        return $this->repository->findByLatAndLonOrFail($data['latitude'],$data['longitude']);
    }

    public function geohash(array $data): string
    {
        $location = $this->repository->findByLatAndLonOrFail($data['latitude'],$data['longitude']);

        return Hash::getGeohash($data['latitude'], $data['longitude']);
    }

    public function geohashV2(array $data): string
    {
        return Hash::getBase64($this->geohash($data));
    }

    public function geohashV3(array $data): string
    {
        return '';
    }
}
