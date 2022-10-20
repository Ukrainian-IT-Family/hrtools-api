<?php

namespace Database\Seeders;

use App\Models\PollAnswer;
use App\Models\PollQuestion;
use Illuminate\Database\Seeder;

class PollAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = PollQuestion::all();

        foreach ($questions as $question) {
            $count = $question->type == 4 ? 1 : rand(3, 6);
            $values = $question->type == 4 ?
                ['value' => '', 'poll_question_id' => $question->id] :
                ['poll_question_id' => $question->id];

            PollAnswer::factory($count)->create($values);
        }
    }
}
