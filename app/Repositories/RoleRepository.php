<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository
{
    public function findById(int $id): ?Role
    {
        return Role::find($id);
    }

    public function findByName(string $name): ?Role
    {
        return Role::firstWhere('name', $name);
    }
}
