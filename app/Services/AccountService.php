<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class AccountService
{
    public function create(array $data)
    {
        return User::query()->create([
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function update(User $user, array $data)
    {
        $user->fill([
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->save();

        return $user;
    }

    public function search(array $data): Collection
    {
        $firstName = $data['firstName'] ?? null;
        $lastName = $data['lastName'] ?? null;
        $email = $data['email'] ?? null;
        $from = $data['from'] ?? 0;
        $size = $data['size'] ?? 10;

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
            ->get();
    }
}
