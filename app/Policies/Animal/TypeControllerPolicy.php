<?php

namespace App\Policies\Animal;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TypeControllerPolicy
{
    use HandlesAuthorization;

    public function addTypeToAnimal(User $user)
    {
        return $user->isAdmin() || $user->isChipper();
    }

    public function updateTypeOfAnimal(User $user)
    {
        return $user->isAdmin() || $user->isChipper();
    }

    public function deleteTypeFromAnimal(User $user)
    {
        return $user->isAdmin() || $user->isChipper();
    }
}
