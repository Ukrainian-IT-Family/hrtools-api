<?php

namespace Database\Seeders;

use App\Models\Poll;
use App\Models\PollQuestion;
use App\Models\QuestionType;
use Illuminate\Database\Seeder;

class PollQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = QuestionType::all();
        $polls = Poll::all();

        foreach ($polls as $poll) {
            for ($i = 5; $i <= rand(6, 15); $i++) {
                PollQuestion::factory()->create(
                    [
                        'type' => $types->random()->id,
                        'poll_id' => $poll->id,
                    ]
                );
            }
        }
    }
}
