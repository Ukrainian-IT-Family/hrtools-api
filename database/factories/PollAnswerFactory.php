<?php

namespace Database\Factories;

use App\Models\PollQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PollAnswer>
 */
class PollAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'value' => $this->faker->realText(20),
            'poll_question_id' => PollQuestion::factory(),
        ];
    }
}
