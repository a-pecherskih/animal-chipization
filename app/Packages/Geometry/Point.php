<?php

namespace App\Packages\Geometry;

interface Point {
    public function __construct($latitude, $longitude);
    public function getLat();
    public function getLng();
    public function isSame(Point $point);
}
