<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GetVacationOrHospitalInfoApiTest extends TestCase
{
    use RefreshDatabase;

    private string $api_url;

    private mixed $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->api_url = 'api/v1/vacation/info';

        $this->user = User::factory()->create([
            'first_name' => 'TestVacation',
            'last_name' => 'User',
            'email' => 'testuservacation@example.com',
            'password' => Hash::make('Worker123456'),
            'hire_date' => '2022-03-04',
        ]);
    }

    public function test_get_vacation_or_hospital_info_success()
    {
        $this->actingAs($this->user);

        $response = $this->getJson($this->api_url);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['data' => [
                'availableVacationsDays',
                'vacationDaysUsed',
                'hospitalDaysUsed',
            ]]);
    }

    public function test_get_vacation_or_hospital_info_unauthorized_user()
    {
        $response = $this->getJson($this->api_url);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['error' => __('authorize.unauthorized')]);
    }
}
