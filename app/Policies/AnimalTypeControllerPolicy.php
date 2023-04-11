<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnimalTypeControllerPolicy
{
    use HandlesAuthorization;

    public function createAnimalType(User $user)
    {
        return ($user->isAdmin() || $user->isChipper());
    }

    public function updateAnimalType(User $user)
    {
        return ($user->isAdmin() || $user->isChipper());
    }

    public function deleteAnimalType(User $user)
    {
        return $user->isAdmin();
    }
}
