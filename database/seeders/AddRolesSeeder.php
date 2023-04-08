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
                'name' => 'ADMIN'
            ],
            [
                'id' => 2,
                'name' => 'CHIPPER'
            ],
            [
                'id' => 3,
                'name' => 'USER'
            ]
        ];

        foreach ($roles as $role) {
            Role::query()->updateOrCreate(['id' => $role['id']], [
                'name' => $role['name'],
            ]);
        }
    }
}
