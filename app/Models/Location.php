<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Location extends Model
{
    protected $fillable = [
        'latitude',
        'longitude',
    ];

    public $timestamps = false;

    public function animals(): BelongsToMany
    {
        return $this->belongsToMany(
            Animal::class,
            'animal_locations',
            'visited_location_id',
            'animal_id'
        );
    }
}
