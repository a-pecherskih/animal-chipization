<?php

namespace App\Validators;

use App\Exceptions\BadRequestException;
use App\Exceptions\ModelFieldExistsException;
use App\Repositories\AreaRepository;
use App\Services\GeometryService;
use Location\Intersection\Intersection;
use Location\Polygon;

class AreaValidator
{
    private AreaRepository $repository;
    private GeometryService $geometryService;

    /**
     * AreaValidator constructor.
     * @param \App\Repositories\AreaRepository $repository
     * @param \App\Services\GeometryService $geometryService
     */
    public function __construct(AreaRepository $repository, GeometryService $geometryService)
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
    public function borderNotCrossEachOtherOrFail(array $lines)
    {
        $chunks = collect($lines)->chunk(2);

        /** @var array $bordersPart1 */
        $bordersPart1 = $chunks->map(function ($chunk) {
            return $chunk->first();
        })->toArray();

        /** @var array $borders */
        $bordersPart2 = $chunks->map(function ($chunk) {
            return (count($chunk) == 1) ? null : $chunk->last();
        })->filter()->toArray();

        if ($this->geometryService->bordersCrossedEachOther($bordersPart1)
            || $this->geometryService->bordersCrossedEachOther($bordersPart2)
        ) {
            throw new BadRequestException('area borders cross each other');
        }
    }

    /**
     * Граница зоны находятся внутри границ существующей зоны
     */
    public function areaNotInsideOtherAreaOrFail(Polygon $polygonCurrent, Polygon $polygonOther)
    {
        $intersection = new Intersection();

        if ($intersection->intersects($polygonCurrent, $polygonOther) || $intersection->intersects($polygonOther, $polygonCurrent)) {

            if ($this->geometryService->areasHaveCrossBordersNotCommon($polygonCurrent, $polygonOther)) {
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
        $count = 0;

        foreach ($geoPointsCurrent as $geoPoint) {
            if ($polygonOtherArea->contains($geoPoint)) $count++;
        }

        if ($count == count($geoPointsCurrent)) {
            throw new ModelFieldExistsException('has same other area');
        }
    }
}
