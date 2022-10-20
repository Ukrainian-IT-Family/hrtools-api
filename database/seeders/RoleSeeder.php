<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Адміністратор', 'Працівник', 'HR'];
        foreach ($roles as $role) {
            Role::factory()->create(
                [
                    'name' => $role,
                ]
            );
        }
    }
}
