<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationControllerPolicy
{
    use HandlesAuthorization;

    public function createLocation(User $user)
    {
        return ($user->isAdmin() || $user->isChipper());
    }

    public function updateLocation(User $user)
    {
        return ($user->isAdmin() || $user->isChipper());
    }

    public function deleteLocation(User $user)
    {
        return $user->isAdmin();
    }
}
