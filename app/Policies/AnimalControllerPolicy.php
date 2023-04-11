<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnimalControllerPolicy
{
    use HandlesAuthorization;

    public function createAnimal(User $user)
    {
        return $user->isAdmin() || $user->isChipper();
    }

    public function updateAnimal(User $user)
    {
        return $user->isAdmin() || $user->isChipper();
    }

    public function deleteAnimal(User $user)
    {
        return $user->isAdmin();
    }
}
