<?php

namespace Database\Factories;

use App\Models\Poll;
use App\Models\QuestionType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PollQuestion>
 */
class PollQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->realText(50),
            'required' => $this->faker->boolean(25),
            'type' => QuestionType::factory(),
            'poll_id' => Poll::factory(),
        ];
    }
}
