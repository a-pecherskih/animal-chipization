<?php

namespace App\Validators;

use App\Exceptions\BadRequestException;
use App\Exceptions\ModelFieldExistsException;
use App\Models\User;
use App\Repositories\AccountRepository;

class AccountValidator
{
    private AccountRepository $repository;

    /**
     * AccountValidator constructor.
     * @param \App\Repositories\AccountRepository $repository
     */
    public function __construct(AccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function checkNotExistUserEmailOrFail(string $email, ?User $ignoreUser = null)
    {
        $user = $this->repository->findByEmail($email);

        if ($user && $ignoreUser && ($user->id == $ignoreUser->id)) return true;

        if ($user) {
            throw new ModelFieldExistsException();
        }
    }

    public function checkNotAnimalsOrFail(User $user)
    {
        if ($user->animals->count()) {
            throw new BadRequestException();
        }
    }
}
