<?php

namespace App\Policies\Animal;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitedLocationControllerPolicy
{
    use HandlesAuthorization;

    public function addVisitedPointToAnimal(User $user)
    {
        return $user->isAdmin() || $user->isChipper();
    }

    public function updateVisitedPointOfAnimal(User $user)
    {
        return $user->isAdmin() || $user->isChipper();
    }

    public function deleteVisitedPointFromAnimal(User $user)
    {
        return $user->isAdmin();
    }
}
