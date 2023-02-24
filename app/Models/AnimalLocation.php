<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalLocation extends Model
{
    public $timestamps = false;

    protected $dateFormat = 'c';

    protected $dates = ['date_time'];

    protected $fillable = ['animal_id', 'visited_location_id', 'date_time'];
}
