<?php

namespace Tests\Feature;

use App\Models\Poll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SendPollToWorkersApiTest extends TestCase
{
    use RefreshDatabase;

    private string $api_url;

    private mixed $user;

    private mixed $poll;

    private array $workers;

    protected $seed = true;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'testUser@example.com',
            'password' => Hash::make('Worker123456'),
            'hire_date' => '2022-03-04',
        ]);

        $this->poll = Poll::factory()->create(
            [
                'title' => 'my poll',
                'status' => Poll::NEW_STATUS_ID,
                'anonymous' => 0,
                'created_by' => $this->user->id,
            ]
        );
        $this->workers = [
            ['id' => User::factory()->create()->id],
            ['id' => User::factory()->create()->id],
            ['id' => User::factory()->create()->id],
        ];
        $this->api_url = 'api/v1/hr/polls/'.$this->poll->id.'/send';
    }

    public function test_send_poll_field_workers_is_array()
    {
        $this->user->role_id = User::HR_ROLE_ID;
        $this->actingAs($this->user);

        $postData = [
            'workers' => 'string',
        ];

        $response = $this->postJson($this->api_url, $postData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'message' => __('validation.array', ['attribute' => 'workers']),
            ]);
    }

    public function test_send_poll_unauthorized()
    {
        $response = $this->postJson($this->api_url);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(
                [
                    'error' => __('authorize.unauthorized'),
                ]
            );
    }

    public function test_send_poll_forbidden_by_role()
    {
        $this->user->role_id = User::WORKER_ROLE_ID;
        $this->actingAs($this->user);
        $response = $this->postJson($this->api_url);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(
                [
                    'error' => __('authorize.forbidden_by_role'),
                ]
            );
    }

    public function test_send_poll_success()
    {
        $this->user->role_id = User::HR_ROLE_ID;
        $this->actingAs($this->user);

        $postData = [
            'workers' => $this->workers,
        ];

        $response = $this->postJson($this->api_url, $postData);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'status',
                    'anonymous',
                    'authorId',
                    'author',
                    'date',
                    'passed',
                    'results',
                    'resultCount',
                    'workersCount',
                    'workers',
                    'questions',
                ],
            ]);

        $this->assertDatabaseHas('poll_user', [
            'poll_id' => $this->poll->id,
            'user_id' => $this->workers[0]['id'],
        ]);
        $this->assertDatabaseHas('poll_user', [
            'poll_id' => $this->poll->id,
            'user_id' => $this->workers[1]['id'],
        ]);
        $this->assertDatabaseHas('poll_user', [
            'poll_id' => $this->poll->id,
            'user_id' => $this->workers[2]['id'],
        ]);
    }
}
