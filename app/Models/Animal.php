<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Animal extends Model
{
    const STATUS_ALIVE = 'ALIVE';
    const STATUS_DEAD = 'DEAD';

    const GENDER_MALE = 'MALE';
    const GENDER_FEMALE = 'FEMALE';
    const GENDER_OTHER = 'OTHER';

    protected $fillable = [
        'weight',
        'length',
        'height',
        'gender',
        'chipper_id',
        'life_status',
        'chipping_location_id',
        'chipping_date_time',
        'death_date_time'
    ];

    public $timestamps = false;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'c';

    protected $dates = ['chipping_date_time', 'death_date_time'];

    public static function getGendersList()
    {
        return [
            self::GENDER_MALE,
            self::GENDER_FEMALE,
            self::GENDER_OTHER,
        ];
    }

    public static function getStatusesList()
    {
        return [
            self::STATUS_ALIVE,
            self::STATUS_DEAD,
        ];
    }

    public function visitedLocations(): BelongsToMany
    {
        return $this->belongsToMany(
            Location::class,
            'animal_locations',
            'animal_id',
            'visited_location_id'
        )->orderBy('visited_location_id');
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(
            AnimalType::class,
            'animal_animal_types',
            'animal_id',
            'animal_type_id'
        );
    }
}
