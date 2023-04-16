<?php

namespace App\Services;

use App\Repositories\AreaRepository;
use App\Validators\AreaValidator;

class AreaService
{
    private AreaRepository $repository;
    private AreaValidator $validator;
    private GeometryService $geometryService;

    /**
     * AreaService constructor.
     * @param \App\Repositories\AreaRepository $repository
     * @param \App\Validators\AreaValidator $validator
     * @param \App\Services\GeometryService $geometryService
     */
    public function __construct(AreaRepository $repository, AreaValidator $validator, GeometryService $geometryService)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->geometryService = $geometryService;
    }

    public function create(array $data)
    {
        $points = $this->geometryService->getPoints($data['areaPoints']);
        $polygon = $this->geometryService->getPolygonFromGeoPoints($points);

        $this->validator->areaDoesntHaveDuplicatePointsOrFail($data['areaPoints']);
        $this->validator->inNotLineOrFail($points);
        $this->validator->borderNotCrossEachOtherOrFail($polygon->getSegments());

        $otherAreas = $this->repository->getOtherAreas();

        foreach ($otherAreas as $area) {
            $pointsOtherArea = $this->geometryService->getPoints($area->points);
            $polygonOtherArea = $this->geometryService->getPolygonFromGeoPoints($pointsOtherArea);

            $this->validator->areaNotInsideOtherAreaOrFail($polygon, $polygonOtherArea);
            $this->validator->areaDoesntHaveSamePointsOrFail($points, $polygonOtherArea);
        }

        return $this->repository->create($data);
    }

    public function update(int $areaId, array $data)
    {
        $area = $this->repository->findByIdOrFail($areaId);

        $this->validator->areaNotExistSameNameOrFail($area, $data['name']);

        $points = $this->geometryService->getPoints($data['areaPoints']);
        $polygon = $this->geometryService->getPolygonFromGeoPoints($points);

        $this->validator->areaDoesntHaveDuplicatePointsOrFail($data['areaPoints']);
        $this->validator->inNotLineOrFail($points);
        $this->validator->borderNotCrossEachOtherOrFail($polygon->getSegments());

        $otherAreas = $this->repository->getOtherAreas($area->id);

        foreach ($otherAreas as $areaOther) {
            $pointsOtherArea = $this->geometryService->getPoints($areaOther->points);
            $polygonOtherArea = $this->geometryService->getPolygonFromGeoPoints($pointsOtherArea);

            $this->validator->areaNotInsideOtherAreaOrFail($polygon, $polygonOtherArea);
            $this->validator->areaDoesntHaveSamePointsOrFail($points, $polygonOtherArea);
        }

        return $this->repository->update($area, $data);
    }

    public function delete(int $areaId)
    {
        $area = $this->repository->findByIdOrFail($areaId);

        $this->repository->delete($area);
    }
}
