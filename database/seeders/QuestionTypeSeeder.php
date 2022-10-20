<?php

namespace Database\Seeders;

use App\Models\QuestionType;
use Illuminate\Database\Seeder;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['Checkbox', 'Radiobutton', 'Select', 'Текстова відповідь'];
        foreach ($types as $type) {
            QuestionType::factory()->create(['name' => $type]);
        }
    }
}
