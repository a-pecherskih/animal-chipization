<?php

namespace App\Packages\Geometry\Geo;

use App\Packages\Geometry\Polygon;
use Location\Polygon as LocationPolygon;

class GeoPolygon implements Polygon
{
    private LocationPolygon $polygon;


    /**
     * GeoPolygon constructor.
     * @param \App\Packages\Geometry\Geo\GeoPoint[] $points
     */
    public function __construct(array $points)
    {
        $this->polygon = new LocationPolygon();
        foreach ($points as $point) {
            $this->polygon->addPoint($point->getPoint());
        }
    }

    /**
     * @return GeoLine[]
     */
    public function getBorders(): array
    {
        $lines = $this->polygon->getSegments();
        $borders = [];

        foreach ($lines as $line) {
            $geoPoint1 = new GeoPoint($line->getPoint1()->getLat(), $line->getPoint1()->getLng());
            $geoPoint2 = new GeoPoint($line->getPoint2()->getLat(), $line->getPoint2()->getLng());

            $borders[] = new GeoLine($geoPoint1, $geoPoint2);
        }

        return $borders;
    }

    /**
     * @return \Location\Polygon
     */
    public function getPolygon()
    {
        return $this->polygon;
    }
}
