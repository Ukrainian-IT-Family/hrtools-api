<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VacationOrHospital>
 */
class VacationOrHospitalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $dateStart = Carbon::parse($this->faker->dateTimeBetween('-3 month', '+1 month'));
        $daysCount = $this->faker->numberBetween(1, 5);

        return [
            'type' => $this->faker->boolean(70),
            'date_start' => $dateStart->format('Y-m-d'),
            'date_end' => $dateStart->addDays($daysCount)->format('Y-m-d'),
            'days_count' => $daysCount,
            'status' => $this->faker->boolean(80) ? $this->faker->boolean(70) : null,
            'comment' => $this->faker->boolean(30) ? $this->faker->realText(150) : null,
            'user_id' => User::factory(),
        ];
    }
}
