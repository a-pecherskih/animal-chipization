<?php

namespace App\Validators;

use App\Exceptions\BadRequestException;
use App\Exceptions\ModelFieldExistsException;
use App\Packages\Geometry\Geometry;
use App\Packages\Geometry\Polygon;
use App\Repositories\AreaRepository;

class AreaValidator
{
    private AreaRepository $repository;
    private Geometry $geometryService;

    /**
     * AreaValidator constructor.
     * @param \App\Repositories\AreaRepository $repository
     * @param \App\Packages\Geometry\Geometry $geometryService
     */
    public function __construct(AreaRepository $repository, Geometry $geometryService)
    {
        $this->repository = $repository;
        $this->geometryService = $geometryService;
    }

    /**
     * Зона с таким name уже существует
     */
    public function areaNotExistSameNameOrFail($area, string $name)
    {
        $areaDB = $this->repository->findByName($name);

        if ($areaDB && $areaDB->id != $area->id) {
            throw new ModelFieldExistsException();
        }
    }

    /**
     * Все точки лежат на одной прямой
     */
    public function inNotLineOrFail(array $geoPoints)
    {
        if ($this->geometryService->isLine($geoPoints)) {
            throw new BadRequestException('area is line');
        }
    }

    /**
     * Границы новой зоны пересекаются между собой
     *  (ищем пересечение границ через одну)
     */
    public function borderNotCrossEachOtherOrFail(Polygon $polygon)
    {
        if ($this->geometryService->polygonLinesCrossedEachOther($polygon)) {
            throw new BadRequestException('area borders cross each other');
        }
    }

    /**
     * Граница зоны находятся внутри границ существующей зоны
     */
    public function areaNotInsideOtherAreaOrFail(Polygon $polygonCurrent, Polygon $polygonOther)
    {
        if ($this->geometryService->polygonIntersectsOtherPolygon($polygonCurrent, $polygonOther)) {
            if ($this->geometryService->polygonsHaveCrossBordersNotCommon($polygonCurrent, $polygonOther)) {
                throw new BadRequestException('area inside other area');
            }
        }
    }

    /**
     * Новая зона имеет дубликаты точек
     */
    public function areaDoesntHaveDuplicatePointsOrFail(array $areaPoints)
    {
        $groupsByCoords = collect($areaPoints)->groupBy(function (array $item, int $key) {
            return $item['longitude'] . '-' . $item['latitude'];
        });

        foreach ($groupsByCoords as $group) {
            if (count($group) > 1) {
                throw new BadRequestException('area has duplicate points');
            }
        }
    }

    /**
     * Зона, состоящая из таких точек, уже существует
     */
    public function areaDoesntHaveSamePointsOrFail(array $geoPointsCurrent, Polygon $polygonOtherArea)
    {
        $points = $this->geometryService->getPointsHasPolygon($geoPointsCurrent, $polygonOtherArea);
        $count = count($points);

        if ($count == count($geoPointsCurrent)) {
            throw new ModelFieldExistsException('has same other area');
        }
    }
}
