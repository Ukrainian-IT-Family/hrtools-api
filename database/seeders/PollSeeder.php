<?php

namespace Database\Seeders;

use App\Models\Poll;
use App\Models\User;
use Illuminate\Database\Seeder;

class PollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hrManagers = User::where([['role_id', 3]])->get();

        foreach ($hrManagers as $hr) {
            Poll::factory(rand(10, 15))->create(['created_by' => $hr->id]);
        }
    }
}
