<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MaritalStatusSeeder::class);
        $this->call(RelationshipSeeder::class);
        $this->call(QuestionTypeSeeder::class);

        $this->call(RoleSeeder::class);

        $user = \App\Models\User::factory()->create([
            'profile_image' => null,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('123123Aa'),
            'email_verified_at' => now(),
        ]);

        $admin = User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('Admin123'),
            'email_verified_at' => now(),
            'role_id' => '1',
        ]);

        $managers = User::factory(5)->create([
            'password' => Hash::make('Hr123123'),
            'role_id' => 3,
            'email_verified_at' => now(),
        ]);

        foreach ($managers as $manager) {
            User::factory(rand(3, 6))->create([
                'manager_id' => $manager->id,
                'password' => Hash::make('Worker123'),
                'role_id' => 2,
                'email_verified_at' => now(),
            ]);
        }

        $this->call(PhoneSeeder::class);
        $this->call(ChildSeeder::class);
        $this->call(EmergencyContactSeeder::class);
        $this->call(EmergencyPhoneSeeder::class);

        $this->call(PollSeeder::class);
        $this->call(PollQuestionSeeder::class);
        $this->call(PollAnswerSeeder::class);
        $this->call(PollUserSeeder::class);
        $this->call(PollResultSeeder::class);
        $this->call(PollAnswerPollResultSeeder::class);
        $this->call(VacationOrHospitalSeeder::class);
    }
}
