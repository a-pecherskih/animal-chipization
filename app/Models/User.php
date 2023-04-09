<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    public $timestamps = false;

    public function animals(): HasMany
    {
        return $this->hasMany(
            Animal::class,
            'chipper_id'
        );
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function isSameUser($user)
    {
        return $user->id == $this->id;
    }

    public function isAdmin()
    {
        return $this->role->name == Role::ADMIN;
    }

    public function isChipper()
    {
        return $this->role->name == Role::CHIPPER;
    }

    public function isUser()
    {
        return $this->role->name == Role::USER;
    }
}
