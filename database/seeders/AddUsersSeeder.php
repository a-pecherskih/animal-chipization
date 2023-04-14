<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AddUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'id' => 1,
                'firstName' => 'adminFirstName',
                'lastName' => 'adminLastName',
                'email' => 'admin@simbirsoft.com',
                'password' => 'qwerty123',
                'role_id' => 1,
            ],
            [
                'id' => 2,
                'firstName' => 'chipperFirstName',
                'lastName' => 'chipperLastName',
                'email' => 'chipper@simbirsoft.com',
                'password' => 'qwerty123',
                'role_id' => 2,
            ],
            [
                'id' => 3,
                'firstName' => 'userFirstName',
                'lastName' => 'userLastName',
                'email' => 'user@simbirsoft.com',
                'password' => 'qwerty123',
                'role_id' => 3,
            ]
        ];

        foreach ($users as $user) {
            User::query()->create([
                'first_name' => $user['firstName'],
                'last_name' => $user['lastName'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
                'role_id' => $user['role_id'],
            ]);
        }
    }
}
