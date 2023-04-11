<?php

namespace App\Policies;

use App\Exceptions\ModelNotFoundException;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountControllerPolicy
{
    use HandlesAuthorization;

    public function getAccount(User $user, ?User $account)
    {
        if (!$user->isAdmin() && (optional($account)->id != $user->id)) {
            return false;
        }
        if ($user->isAdmin() && blank($account)) {
            throw new ModelNotFoundException();
        }

        return true;
    }

    public function searchAccount(User $user)
    {
        return $user->isAdmin();
    }

    public function createAccount(User $user)
    {
        return $user->isAdmin();
    }

    public function updateAccount(User $user, ?User $account)
    {
        if (!$user->isAdmin() && (optional($account)->id != $user->id)) {
            return false;
        }
        if ($user->isAdmin() && blank($account)) {
            throw new ModelNotFoundException();
        }

        return true;
    }

    public function deleteAccount(User $user, ?User $account)
    {
        if (!$user->isAdmin() && (optional($account)->id != $user->id)) {
            return false;
        }
        if ($user->isAdmin() && blank($account)) {
            throw new ModelNotFoundException();
        }

        return true;
    }
}
