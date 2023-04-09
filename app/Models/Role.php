<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const ADMIN = 'ADMIN';
    const CHIPPER = 'CHIPPER';
    const USER = 'USER';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public static function getRoles()
    {
        return [
          self::ADMIN,
          self::CHIPPER,
          self::USER,
        ];
    }
}
