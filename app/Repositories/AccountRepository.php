<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class AccountRepository
{
    public function findByIdOrFail(int $id, $with = ['role']): User
    {
        return User::query()
            ->with($with)
            ->findOrFail($id);
    }

    public function findById(int $id, $with = ['role']): ?User
    {
        return User::query()
            ->with($with)
            ->where('id', $id)
            ->first();
    }

    public function findByEmail(string $email, $with = ['role']): ?User
    {
        return User::query()
            ->with($with)
            ->where('email', $email)
            ->first();
    }

    public function create(array $data): User
    {
        return User::query()->create([
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id']
        ]);
    }

    public function update(User $user, array $data): User
    {
        $user->fill([
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id']
        ]);
        $user->save();

        $user->load('role');

        return $user;
    }

    public function search(array $filters): Collection
    {
        $firstName = $filters['firstName'] ?? null;
        $lastName = $filters['lastName'] ?? null;
        $email = $filters['email'] ?? null;
        $from = $filters['from'] ?? 0;
        $size = $filters['size'] ?? 10;

        return User::query()
            ->when($firstName, function (Builder $q) use ($firstName) {
                $q->where('first_name', 'like', "%$firstName%");
            })
            ->when($lastName, function (Builder $q) use ($lastName) {
                $q->where('last_name', 'like', "%$lastName%");
            })
            ->when($email, function (Builder $q) use ($email) {
                $q->where('email', 'like', "%$email%");
            })
            ->offset($from)
            ->limit($size)
            ->orderBy('id')
            ->with('role')
            ->get();
    }
}
