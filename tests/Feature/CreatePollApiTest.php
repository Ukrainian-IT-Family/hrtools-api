<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\QuestionTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreatePollApiTest extends TestCase
{
    use RefreshDatabase;

    private string $api_url;

    private mixed $user;

    private string $title;

    private bool $anonymous;

    private array $questions;

    public function setUp(): void
    {
        parent::setUp();

        $this->api_url = 'api/v1/hr/polls';

        $this->user = User::factory()->create([
            'first_name' => 'TestVacation',
            'last_name' => 'User',
            'email' => 'testuservacation@example.com',
            'password' => Hash::make('Worker123456'),
            'hire_date' => '2022-03-04',
        ]);

        $this->title = 'my poll';
        $this->anonymous = false;
        $this->questions = [[
            'name' => 'question 1',
            'required' => 0,
            'type' => 4,
            'answers' => [['value' => null]],
        ]];
    }

    public function test_create_poll_required_fields()
    {
        $this->user->role_id = User::HR_ROLE_ID;
        $this->actingAs($this->user);

        $response = $this->postJson($this->api_url);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'errors' => [
                    'title' => [__('validation.required', ['attribute' => 'title'])],
                    'anonymous' => [__('validation.required', ['attribute' => 'anonymous'])],
                    'questions' => [__('validation.required', ['attribute' => 'questions'])],
                ],
            ]);
    }

    public function test_create_poll_field_anonymous_is_boolean()
    {
        $this->user->role_id = User::HR_ROLE_ID;
        $this->actingAs($this->user);

        $postData = [
            'title' => $this->title,
            'anonymous' => 'string',
            'questions' => $this->questions,
        ];

        $response = $this->postJson($this->api_url, $postData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'message' => __('validation.boolean', ['attribute' => 'anonymous']),
            ]);
    }

    public function test_create_poll_field_required_is_boolean()
    {
        $this->user->role_id = User::HR_ROLE_ID;
        $this->actingAs($this->user);

        $postData = [
            'title' => $this->title,
            'anonymous' => $this->anonymous,
            'questions' => [
                [
                    'name' => 'question 1',
                    'required' => 'string',
                    'type' => 4,
                    'answers' => [
                        ['value' => null],
                    ],
                ],
            ],
        ];

        $response = $this->postJson($this->api_url, $postData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'message' => __('validation.boolean', ['attribute' => 'questions.0.required']),
            ]);
    }

    public function test_create_poll_max_count_questions()
    {
        $this->user->role_id = User::HR_ROLE_ID;
        $this->actingAs($this->user);

        $postData = [
            'title' => $this->title,
            'anonymous' => $this->anonymous,
            'questions' => [
                [
                    'name' => 'question 1',
                    'required' => 0,
                    'type' => 4,
                    'answers' => [['value' => null]],
                ],
                [
                    'name' => 'question 2',
                    'required' => 0,
                    'type' => 4,
                    'answers' => [['value' => null]],
                ],
                [
                    'name' => 'question 3',
                    'required' => 0,
                    'type' => 4,
                    'answers' => [['value' => null]],
                ],
                [
                    'name' => 'question 4',
                    'required' => 0,
                    'type' => 4,
                    'answers' => [['value' => null]],
                ],
                [
                    'name' => 'question 5',
                    'required' => 0,
                    'type' => 4,
                    'answers' => [['value' => null]],
                ],
                [
                    'name' => 'question 6',
                    'required' => 0,
                    'type' => 4,
                    'answers' => [['value' => null]],
                ],
                [
                    'name' => 'question 7',
                    'required' => 0,
                    'type' => 4,
                    'answers' => [['value' => null]],
                ],
                [
                    'name' => 'question 8',
                    'required' => 0,
                    'type' => 4,
                    'answers' => [['value' => null]],
                ],
                [
                    'name' => 'question 9',
                    'required' => 0,
                    'type' => 4,
                    'answers' => [['value' => null]],
                ],
                [
                    'name' => 'question 10',
                    'required' => 0,
                    'type' => 4,
                    'answers' => [['value' => null]],
                ],
                [
                    'name' => 'question 11',
                    'required' => 0,
                    'type' => 4,
                    'answers' => [['value' => null]],
                ],
                [
                    'name' => 'question 12',
                    'required' => 0,
                    'type' => 4,
                    'answers' => [['value' => null]],
                ],
                [
                    'name' => 'question 13',
                    'required' => 0,
                    'type' => 4,
                    'answers' => [['value' => null]],
                ],
                [
                    'name' => 'question 14',
                    'required' => 0,
                    'type' => 4,
                    'answers' => [['value' => null]],
                ],
                [
                    'name' => 'question 15',
                    'required' => 0,
                    'type' => 4,
                    'answers' => [['value' => null]],
                ],
                [
                    'name' => 'question 16',
                    'required' => 0,
                    'type' => 4,
                    'answers' => [['value' => null]],
                ],
            ],
        ];

        $response = $this->postJson($this->api_url, $postData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'message' => __('validation.max.array', ['attribute' => 'questions', 'max' => '15']),
            ]);
    }

    public function test_create_poll_max_count_answers()
    {
        $this->user->role_id = User::HR_ROLE_ID;
        $this->actingAs($this->user);

        $postData = [
            'title' => $this->title,
            'anonymous' => $this->anonymous,
            'questions' => [
                [
                    'name' => 'question 1',
                    'required' => 0,
                    'type' => 3,
                    'answers' => [
                        ['value' => 'answer 1'],
                        ['value' => 'answer 2'],
                        ['value' => 'answer 3'],
                        ['value' => 'answer 4'],
                        ['value' => 'answer 5'],
                        ['value' => 'answer 6'],
                        ['value' => 'answer 7'],
                    ],
                ],
            ],
        ];

        $response = $this->postJson($this->api_url, $postData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'message' => __('validation.max.array', ['attribute' => 'questions.0.answers', 'max' => '6']),
            ]);
    }

    public function test_create_poll_unauthorized()
    {
        $response = $this->postJson($this->api_url);

        $response
            ->assertStatus(400)
            ->assertJsonFragment([
                'error' => __('authorize.unauthorized'),
            ]);
    }

    public function test_create_poll_forbidden_by_role()
    {
        $this->user->role_id = User::WORKER_ROLE_ID;
        $this->actingAs($this->user);
        $response = $this->postJson($this->api_url);

        $response
            ->assertStatus(400)
            ->assertJsonFragment([
                'error' => __('authorize.forbidden_by_role'),
            ]);
    }

    public function test_create_poll_success()
    {
        $this->seed(QuestionTypeSeeder::class);
        $this->user->role_id = User::HR_ROLE_ID;
        $this->actingAs($this->user);
        $postData = [
            'title' => $this->title,
            'anonymous' => $this->anonymous,
            'questions' => $this->questions,
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
    }
}
