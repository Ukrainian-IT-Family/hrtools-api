<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class ForgotPasswordApiTest extends TestCase
{
    use RefreshDatabase;

    private string $forgot_api_url;

    private string $email;

    private mixed $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->forgot_api_url = 'api/v1/auth/forgot-password';
        $this->email = Str::random(5).'@'.Str::random(5).'.com';
        $this->user = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => $this->email,
            'password' => Hash::make('RandomPassword123'),
        ]);
    }

    public function test_forgot_email_send_success()
    {
        $response = $this->postJson($this->forgot_api_url, [
            'email' => $this->user->email,
        ]);

        $response
            ->assertJsonFragment(['data' => ['msg' => __('passwords.sent')]])
            ->assertStatus(200);
    }

    public function test_user_not_exist()
    {
        $response = $this->postJson($this->forgot_api_url, [
            'email' => fake()->email(),
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['message' => __('validation.exists', ['attribute' => __('validation.attributes.email')])]);
    }

    public function test_required_fields_for_forgot_password()
    {
        $response = $this->postJson($this->forgot_api_url, []);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => __('validation.required', ['attribute' => __('validation.attributes.email')]),
                'errors' => [
                    'email' => [__('validation.required', ['attribute' => __('validation.attributes.email')])],
                ],
            ]);
    }

    public function test_incorrect_email()
    {
        $Data = ['email' => 'john-example.com'];

        $response = $this->postJson($this->forgot_api_url, $Data);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['message' => __('validation.email', ['attribute' => __('validation.attributes.email')])]);
    }
}
