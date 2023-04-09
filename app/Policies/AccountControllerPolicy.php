<?php

namespace App\Policies;

use App\Exceptions\ForbiddenException;
use App\Exceptions\ModelNotFoundException;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountControllerPolicy
{
    use HandlesAuthorization;

    public function getAccount(User $authUser, ?User $user)
    {
        if (!$authUser->isAdmin() && (optional($user)->id != $authUser->id)) {
            throw new ForbiddenException();
        }
        if ($authUser->isAdmin() && blank($user)) {
            throw new ModelNotFoundException();
        }

        return true;
    }

    public function searchAccount(User $authUser)
    {
        if (!$authUser->isAdmin()) {
            throw new ForbiddenException();
        }

        return true;
    }

    public function storeAccount(User $authUser)
    {
        if (!$authUser->isAdmin()) {
            throw new ForbiddenException();
        }

        return true;
    }

    public function updateAccount(User $authUser, ?User $user)
    {
        if (!$authUser->isAdmin() && (optional($user)->id != $authUser->id)) {
            throw new ForbiddenException();
        }
        if ($authUser->isAdmin() && blank($user)) {
            throw new ModelNotFoundException();
        }

        return true;
    }

    public function deleteAccount(User $authUser, ?User $user)
    {
        if (!$authUser->isAdmin() && (optional($user)->id != $authUser->id)) {
            throw new ForbiddenException();
        }
        if ($authUser->isAdmin() && blank($user)) {
            throw new ModelNotFoundException();
        }

        return true;
    }
}
