<?php

namespace Database\Seeders;

use App\Models\Relationship;
use Illuminate\Database\Seeder;

class RelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $relation_values = ['чоловік', 'дружина', 'мати', 'батько', 'брат', 'сестра', 'інше'];
        foreach ($relation_values as $relation) {
            Relationship::factory()->create([
                'name' => $relation,
            ]);
        }
    }
}
