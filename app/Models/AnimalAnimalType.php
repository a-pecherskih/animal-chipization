<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalAnimalType extends Model
{
    public $timestamps = false;

    protected $fillable = ['animal_id', 'animal_type_id'];
}
