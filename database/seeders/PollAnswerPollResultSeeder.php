<?php

namespace Database\Seeders;

use App\Models\PollAnswer;
use App\Models\PollQuestion;
use App\Models\PollResult;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PollAnswerPollResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pollResults = PollResult::all();
        foreach ($pollResults as $pollResult) {
            $pollQuestions = PollQuestion::where('poll_id', $pollResult->poll_id)->pluck('id')->toArray();

            $pollAnswers = PollAnswer::query()->whereIn('poll_question_id', $pollQuestions)->pluck('id')->toArray();
            $answers = Arr::random($pollAnswers, rand(1, count($pollAnswers)));

            $pollResult->pollAnswers()->sync($answers);
            $pollResult->save();
        }
    }
}
