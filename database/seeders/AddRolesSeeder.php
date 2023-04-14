<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class AddRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'id' => 1,
                'name' => Role::ADMIN
            ],
            [
                'id' => 2,
                'name' => Role::CHIPPER
            ],
            [
                'id' => 3,
                'name' => Role::USER
            ]
        ];

        foreach ($roles as $role) {
            Role::query()->create([
                'name' => $role['name'],
            ]);
        }
    }
}
