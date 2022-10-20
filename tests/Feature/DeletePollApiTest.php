<?php

namespace Tests\Feature;

use App\Models\Poll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DeletePollApiTest extends TestCase
{
    use RefreshDatabase;

    private string $api_url;

    private mixed $user;

    private mixed $poll;

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
        $this->api_url = 'api/v1/hr/polls/'.$this->poll->id;
    }

    public function test_delete_poll_unauthorized()
    {
        $response = $this->deleteJson($this->api_url);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(
                [
                    'error' => __('authorize.unauthorized'),
                ]
            );
    }

    public function test_delete_poll_forbidden_by_role()
    {
        $this->user->role_id = User::WORKER_ROLE_ID;
        $this->actingAs($this->user);
        $response = $this->deleteJson($this->api_url);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(
                [
                    'error' => __('authorize.forbidden_by_role'),
                ]
            );
    }

    public function test_delete_poll_success()
    {
        $this->user->role_id = User::HR_ROLE_ID;
        $this->actingAs($this->user);

        $response = $this->deleteJson($this->api_url);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('polls', [
            'id' => $this->poll->id,
            'created_by' => $this->user->id,
        ]);
    }
}
