<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\RoleRepository;
use App\Validators\AccountValidator;
use Illuminate\Database\Eloquent\Collection;

class AccountService
{
    private AccountRepository $repository;
    private RoleRepository $roleRepository;
    private AccountValidator $validator;

    /**
     * AccountService constructor.
     * @param \App\Repositories\AccountRepository $repository
     * @param \App\Repositories\RoleRepository $roleRepository
     * @param \App\Validators\AccountValidator $validator
     */
    public function __construct(AccountRepository $repository, RoleRepository $roleRepository, AccountValidator $validator)
    {
        $this->repository = $repository;
        $this->roleRepository = $roleRepository;
        $this->validator = $validator;
    }

    public function register(array $data)
    {
        $this->validator->checkNotExistUserEmailOrFail($data['email']);

        $roleId = $this->roleRepository->findByName(Role::USER)->id;
        $data['role_id'] = $roleId;

        return $this->repository->create($data);
    }

    /**
     * @throws \App\Exceptions\ModelFieldExistsException
     */
    public function create(array $data)
    {
        $this->validator->checkNotExistUserEmailOrFail($data['email']);

        $roleId = $this->roleRepository->findByName($data['role'])->id;
        $data['role_id'] = $roleId;

        return $this->repository->create($data);
    }

    /**
     * @throws \App\Exceptions\ModelFieldExistsException
     */
    public function update(User $user, array $data): User
    {
        $this->validator->checkNotExistUserEmailOrFail($data['email'], $user);

        $roleId = $this->roleRepository->findByName($data['role'])->id;
        $data['role_id'] = $roleId;

        return $this->repository->update($user, $data);
    }

    public function search(array $data): Collection
    {
       return $this->repository->search($data);
    }

    /**
     * @throws \App\Exceptions\BadRequestException
     */
    public function delete(User $user)
    {
       $this->validator->checkNotAnimalsOrFail($user);

       $this->repository->delete($user);
    }
}
