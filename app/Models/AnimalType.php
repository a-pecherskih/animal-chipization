<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AnimalType extends Model
{
    protected $fillable = ['type'];

    public $timestamps = false;

    public function animals(): BelongsToMany
    {
        return $this->belongsToMany(
            Animal::class,
            'animal_animal_types',
            'animal_type_id',
            'animal_id'
        );
    }
}
