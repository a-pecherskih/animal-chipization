<?php

namespace App\Packages\Geometry;

interface Geometry {
    public function getPointFromCoordinates($latitude, $longitude): Point;
    public function getPointsFromCoordinates(array $coordinates): array;
    public function getPolygonFromPoints(array $points): Polygon;
    public function polygonHasPoint(Polygon $polygon, Point $point): bool;
    public function isLine(array $points): bool;
    public function polygonIntersectsOtherPolygon(Polygon $polygon1, Polygon $polygon2): bool;
    public function polygonsHaveCrossBordersNotCommon(Polygon $polygon1, Polygon $polygon2): bool;
    public function getPointsHasPolygon(array $points, Polygon $polygon): array;
    public function polygonLinesCrossedEachOther(Polygon $polygon): bool;
}
