<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class VacationOrHospitalCreateApiTest extends TestCase
{
    use RefreshDatabase;

    private string $api_url;

    private mixed $user;

    private int $type;

    private string $dateStart;

    private string $dateEnd;

    private string $comment;

    public function setUp(): void
    {
        parent::setUp();

        $this->api_url = 'api/v1/vacation';

        $this->user = User::factory()->create([
            'first_name' => 'TestVacation',
            'last_name' => 'User',
            'email' => 'testuservacation@example.com',
            'password' => Hash::make('Worker123456'),
            'hire_date' => '2022-03-04',
        ]);

        $this->type = 0;
        $this->dateStart = '2022-09-04';
        $this->dateEnd = '2022-09-06';
        $this->comment = 'comment for vacation';
    }

    public function test_too_many_vacation_days_are_pending_than_available()
    {
        $this->actingAs($this->user);
        $postData = [
            'type' => $this->type,
            'dateStart' => '2022-09-01',
            'dateEnd' => '2022-09-30',
            'comment' => $this->comment,
        ];

        $response = $this->postJson($this->api_url, $postData);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['message' => __('exceptions.too_many_vacation_days_are_pending_than_available')]);
    }

    public function test_required_fields_vacation_or_hospital_create_request()
    {
        $this->actingAs($this->user);
        $response = $this->postJson($this->api_url, []);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'errors' => [
                    'type' => [__('validation.required', ['attribute' => 'type'])],
                    'dateStart' => [__('validation.required', ['attribute' => 'date start'])],
                    'dateEnd' => [__('validation.required', ['attribute' => 'date end'])],
                ],
            ]);
    }

    public function TestDateEndMustBeBiggerThanDateStart()
    {
        $this->actingAs($this->user);
        $postData = [
            'type' => $this->type,
            'dateStart' => '2022-09-31',
            'dateEnd' => '2022-09-29',
            'comment' => $this->comment,
        ];

        $response = $this->postJson($this->api_url, $postData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(
                ['message' => __('validation.after', ['attribute' => 'dateEnd', 'date' => '2022-09-31'])]
            );
    }

    public function testMaxLongComment()
    {
        $this->actingAs($this->user);
        $postData = [
            'type' => $this->type,
            'dateStart' => $this->dateStart,
            'dateEnd' => $this->dateEnd,
            'comment' => Str::random(250),
        ];

        $response = $this->postJson($this->api_url, $postData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(
                ['message' => __('validation.max.string', ['attribute' => 'comment', 'max' => 200])]
            );
    }

    public function TestSuccessfulVacationRequest()
    {
        $this->actingAs($this->user);
        $postData = [
            'type' => $this->type,
            'dateStart' => $this->dateStart,
            'dateEnd' => $this->dateEnd,
            'comment' => $this->comment,
        ];

        $response = $this->postJson($this->api_url, $postData);

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
                    'user' => [
                        'id',
                        'avatar',
                        'shortName',
                        'fullName',
                        'firstName',
                        'middleName',
                        'lastName',
                        'managerId',
                        'role',
                        'position',
                        'hireDate',
                    ],
                ],
            ]);
    }
}
