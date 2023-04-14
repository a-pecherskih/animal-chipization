<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AreaControllerPolicy
{
    use HandlesAuthorization;

    public function createArea(User $user)
    {
        return $user->isAdmin();
    }

    public function updateArea(User $user)
    {
        return $user->isAdmin();
    }

    public function deleteArea(User $user)
    {
        return $user->isAdmin();
    }
}
