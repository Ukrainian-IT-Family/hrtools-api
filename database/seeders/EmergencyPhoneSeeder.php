<?php

namespace Database\Seeders;

use App\Models\EmergencyContact;
use App\Models\EmergencyPhone;
use Illuminate\Database\Seeder;

class EmergencyPhoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emergencyContacts = EmergencyContact::all();

        foreach ($emergencyContacts as $emergencyContact) {
            EmergencyPhone::factory(rand(1, 2))->create([
                'emergency_contact_id' => $emergencyContact->id,
            ]);
        }
    }
}
