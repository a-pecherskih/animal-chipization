<?php

namespace App\Helpers;

use App\Packages\Geometry\GeoHash;

class Hash {

    public static function getGeohash($latitude, $longitude, $length = 12): string
    {
        return (new GeoHash())->encode($latitude, $longitude, $length);
    }

    public static function getBase64($str): string
    {
        return base64_encode($str);
    }
}
