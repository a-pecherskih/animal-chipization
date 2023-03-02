<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = [
        'latitude',
        'longitude',
    ];

    public $timestamps = false;

    public function chippingAnimals(): HasMany
    {
        return $this->hasMany(
            Animal::class,
            'chipping_location_id',
        );
    }

    public function visitedAnimals(): BelongsToMany
    {
        return $this->belongsToMany(
            Animal::class,
            'animal_locations',
            'visited_location_id',
            'animal_id'
        );
    }
}
