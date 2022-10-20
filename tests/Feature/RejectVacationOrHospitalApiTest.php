<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VacationOrHospital;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class RejectVacationOrHospitalApiTest extends TestCase
{
    use RefreshDatabase;

    private string $api_url_for_hr;

    private string $api_url_for_admin;

    private mixed $user;

    private mixed $hr;

    private mixed $admin;

    private mixed $hospital_hr;

    private mixed $hospital_worker;

    protected $seeder = RoleSeeder::class;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Smith123456'),
            'remember_token' => Str::random(10),
        ]);

        $this->hr = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Hr',
            'email' => 'hr@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Smith123456'),
            'remember_token' => Str::random(10),
        ]);

        $this->admin = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Hr',
            'email' => 'admin0@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Smith123456'),
            'remember_token' => Str::random(10),
        ]);

        $this->hospital_worker = VacationOrHospital::factory()->create(
            [
                'type' => VacationOrHospital::HOSPITAL_ID,
                'date_start' => '2022-09-29',
                'date_end' => '2022-09-30',
                'days_count' => 1,
                'status' => VacationOrHospital::PENDING_STATUS,
                'user_id' => $this->user->id,
            ]
        );

        $this->hospital_hr = VacationOrHospital::factory()->create(
            [
                'type' => VacationOrHospital::HOSPITAL_ID,
                'date_start' => '2022-09-29',
                'date_end' => '2022-09-30',
                'days_count' => 1,
                'status' => VacationOrHospital::PENDING_STATUS,
                'user_id' => $this->hr->id,
            ]
        );

        $this->api_url_for_hr = 'api/v1/hr/vacation/'.$this->hospital_worker->id.'/cancel';
        $this->api_url_for_admin = 'api/v1/admin/vacation/'.$this->hospital_hr->id.'/cancel';
    }

    public function test_reject_vacation_or_hospital_request_for_worker_by_hr_success()
    {
        $this->hr->role_id = User::HR_ROLE_ID;
        $this->user->role_id = User::WORKER_ROLE_ID;
        $this->user->manager_id = $this->hr->id;

        $this->actingAs($this->hr);

        $this->assertDatabaseHas('vacation_or_hospitals', [
            'status' => VacationOrHospital::PENDING_STATUS,
            'user_id' => $this->user->id,
        ]);

        $response = $this->postJson($this->api_url_for_hr);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'type',
                    'dateStart',
                    'dateEnd',
                    'daysCount',
                    'status',
                    'comment',
                    'dateCreate',
                    'userId',
                    'canDelete',
                    'user',
                ],
            ]);

        $this->assertDatabaseHas('vacation_or_hospitals', [
            'status' => VacationOrHospital::CANCEL_STATUS,
            'user_id' => $this->user->id,
        ]);
    }

    public function test_reject_vacation_or_hospital_request_for_hr_by_admin_success()
    {
        $this->admin->role_id = User::ADMIN_ROLE_ID;
        $this->hr->role_id = User::HR_ROLE_ID;

        $this->actingAs($this->admin);

        $this->assertDatabaseHas('vacation_or_hospitals', [
            'status' => VacationOrHospital::PENDING_STATUS,
            'user_id' => $this->hr->id,
        ]);

        $response = $this->postJson($this->api_url_for_admin);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'type',
                    'dateStart',
                    'dateEnd',
                    'daysCount',
                    'status',
                    'comment',
                    'dateCreate',
                    'userId',
                    'canDelete',
                    'user',
                ],
            ]);

        $this->assertDatabaseHas('vacation_or_hospitals', [
            'status' => VacationOrHospital::CANCEL_STATUS,
            'user_id' => $this->hr->id,
        ]);
    }

    public function test_reject_vacation_or_hospital_request_for_hr_forbidden_by_role()
    {
        $this->user->role_id = User::HR_ROLE_ID;
        $this->actingAs($this->user);

        $response = $this->postJson($this->api_url_for_admin);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['error' => __('authorize.forbidden_by_role')]);
    }

    public function test_reject_vacation_or_hospital_request_for_worker_forbidden_by_role()
    {
        $this->admin->role_id = User::ADMIN_ROLE_ID;
        $this->actingAs($this->admin);

        $response = $this->postJson($this->api_url_for_hr);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['error' => __('authorize.forbidden_by_role')]);
    }
}
