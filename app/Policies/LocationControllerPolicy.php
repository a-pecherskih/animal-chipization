<?php

namespace App\Policies;

use App\Exceptions\ForbiddenException;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationControllerPolicy
{
    use HandlesAuthorization;

    public function storeLocation(User $authUser)
    {
        if ($authUser->isAdmin() || $authUser->isChipper()) return true;

        throw new ForbiddenException();
    }

    public function updateLocation(User $authUser)
    {
        if ($authUser->isAdmin() || $authUser->isChipper()) return true;

        throw new ForbiddenException();
    }

    public function deleteLocation(User $authUser)
    {
        if ($authUser->isAdmin()) return true;

        throw new ForbiddenException();
    }
}
