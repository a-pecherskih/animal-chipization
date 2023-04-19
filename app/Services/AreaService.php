<?php

namespace App\Services;

use App\Packages\Geometry\Geometry;
use App\Repositories\AreaRepository;
use App\Validators\AreaValidator;

class AreaService
{
    private AreaRepository $repository;
    private AreaValidator $validator;
    private Geometry $geometryService;

    /**
     * AreaService constructor.
     * @param \App\Repositories\AreaRepository $repository
     * @param \App\Validators\AreaValidator $validator
     * @param \App\Packages\Geometry\Geometry $geometryService
     */
    public function __construct(AreaRepository $repository, AreaValidator $validator, Geometry $geometryService)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->geometryService = $geometryService;
    }

    public function show(int $areaId)
    {
        return $this->repository->findByIdOrFail($areaId);
    }

    public function create(array $data)
    {
        $points = $this->geometryService->getPointsFromCoordinates($data['areaPoints']);
        $polygon = $this->geometryService->getPolygonFromPoints($points);

        $this->validator->areaDoesntHaveDuplicatePointsOrFail($data['areaPoints']);
        $this->validator->inNotLineOrFail($points);
        $this->validator->borderNotCrossEachOtherOrFail($polygon);

        $otherAreas = $this->repository->getOtherAreas();

        foreach ($otherAreas as $area) {
            $pointsOtherArea = $this->geometryService->getPointsFromCoordinates($area->points);
            $polygonOtherArea = $this->geometryService->getPolygonFromPoints($pointsOtherArea);

            $this->validator->areaNotInsideOtherAreaOrFail($polygon, $polygonOtherArea);
            $this->validator->areaDoesntHaveSamePointsOrFail($points, $polygonOtherArea);
        }

        return $this->repository->create($data);
    }

    public function update(int $areaId, array $data)
    {
        $area = $this->repository->findByIdOrFail($areaId);

        $this->validator->areaNotExistSameNameOrFail($area, $data['name']);

        $points = $this->geometryService->getPointsFromCoordinates($data['areaPoints']);
        $polygon = $this->geometryService->getPolygonFromPoints($points);

        $this->validator->areaDoesntHaveDuplicatePointsOrFail($data['areaPoints']);
        $this->validator->inNotLineOrFail($points);
        $this->validator->borderNotCrossEachOtherOrFail($polygon);

        $otherAreas = $this->repository->getOtherAreas($area->id);

        foreach ($otherAreas as $areaOther) {
            $pointsOtherArea = $this->geometryService->getPointsFromCoordinates($areaOther->points);
            $polygonOtherArea = $this->geometryService->getPolygonFromPoints($pointsOtherArea);

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
