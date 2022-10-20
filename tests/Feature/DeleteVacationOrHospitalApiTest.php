<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VacationOrHospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class DeleteVacationOrHospitalApiTest extends TestCase
{
    use RefreshDatabase;

    private string $api_url;

    private mixed $user;

    private mixed $hospital;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'testuser@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Smith123456'),
            'remember_token' => Str::random(10),
        ]);

        $this->hospital = VacationOrHospital::factory()->create(
            [
                'type' => VacationOrHospital::HOSPITAL_ID,
                'date_start' => '2022-09-29',
                'date_end' => '2022-09-30',
                'days_count' => 1,
                'status' => VacationOrHospital::PENDING_STATUS,
                'user_id' => $this->user->id,
            ]
        );

        $this->api_url = 'api/v1/vacation/'.$this->hospital->id;
    }

    public function test_delete_vacation_or_hospital_success()
    {
        $this->actingAs($this->user);

        $this->assertDatabaseHas('vacation_or_hospitals', [
            'user_id' => $this->user->id,
        ]);

        $this->deleteJson($this->api_url);

        $this->assertDatabaseMissing('vacation_or_hospitals', [
            'user_id' => $this->user->id,
        ]);
    }

    public function test_unauthorized_user_delete_vacation_or_hospital()
    {
        $response = $this->deleteJson($this->api_url);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['error' => __('authorize.unauthorized')]);
    }
}
