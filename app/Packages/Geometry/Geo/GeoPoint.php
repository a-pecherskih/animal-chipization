<?php

namespace App\Packages\Geometry\Geo;

use App\Packages\Geometry\Point;
use Location\Coordinate as LocationPoint;

class GeoPoint implements Point {
    private LocationPoint $point;

    /**
     * GeoPoint constructor.
     * @param $latitude
     * @param $longitude
     */
    public function __construct($latitude, $longitude)
    {
        $this->point = new LocationPoint($latitude, $longitude);
    }

    public function getLat()
    {
        return $this->point->getLat();
    }

    public function getLng()
    {
        return $this->point->getLng();
    }

    public function getPoint(): LocationPoint
    {
        return $this->point;
    }

    public function isSame(GeoPoint|Point $point2): bool
    {
        return $this->point->hasSameLocation($point2->getPoint());
    }
}
