<?php

namespace Database\Seeders;

use App\Models\Poll;
use App\Models\PollResult;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PollResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $polls = Poll::all();

        foreach ($polls as $poll) {
            $count = rand(4, $poll->workers_count);
            $workers = Arr::random($poll->workers->toArray(), $count);
            foreach ($workers as $worker) {
                PollResult::factory()->create([
                    'user_id' => $worker['id'],
                    'poll_id' => $poll->id,
                ]);
            }
        }
    }
}
