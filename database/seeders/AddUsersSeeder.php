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
        $data = [
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

        foreach ($data as $item) {
            User::query()->create([
                'id' => $item['id'],
                'first_name' => $item['firstName'],
                'last_name' => $item['lastName'],
                'email' => $item['email'],
                'password' => Hash::make($item['password']),
                'role_id' => $item['role_id'],
            ]);
        }
    }
}
