<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VacationOrHospital;
use Illuminate\Database\Seeder;

class VacationOrHospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            VacationOrHospital::factory(rand(3, 5))->create(['user_id' => $user->id]);
        }
    }
}
