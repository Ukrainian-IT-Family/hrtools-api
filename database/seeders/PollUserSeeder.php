<?php

namespace Database\Seeders;

use App\Models\Poll;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PollUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $polls = Poll::all();
        $allWorkers = User::where([['role_id', 2]])->pluck('id')->toArray();
        foreach ($polls as $poll) {
            $workers = Arr::random($allWorkers, rand(5, 10));
            $poll->workers()->sync($workers);
            $poll->save();
        }
    }
}
