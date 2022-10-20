<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Tests\TestCase;

class ResetPasswordApiTest extends TestCase
{
    use RefreshDatabase;

    private string $reset_api_url;

    private string $email;

    private string $token;

    private string $password;

    private string $password_confirmation;

    private mixed $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->reset_api_url = 'api/v1/auth/reset';
        $this->email = Str::random(5).'@'.Str::random(5).'.com';
        $this->token = Str::random(64);
        $this->password = 'NewPassword123123';
        $this->password_confirmation = 'NewRandomPassword123123';
        $this->user = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => $this->email,
            'password' => Hash::make('RandomPassword123'),
        ]);

        DB::table('password_resets')->insert([
            'email' => $this->email,
            'token' => $this->token,
            'created_at' => Carbon::now(),
        ]);
    }

    public function test_required_fields_for_reset()
    {
        $Data = [
            'email' => $this->email,
            'token' => $this->token,
        ];
        $response = $this->postJson($this->reset_api_url, $Data);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => __('validation.required', ['attribute' => __('validation.attributes.password')]),
                'errors' => [
                    'password' => [__('validation.required', ['attribute' => __('validation.attributes.password')])],
                ],
            ]);
    }

    public function test_repeat_password()
    {
        $Data = [
            'email' => $this->email,
            'token' => $this->token,
            'password' => $this->password,
            'password_confirmation' => 'WrongPassword123',
        ];
        $response = $this->postJson($this->reset_api_url, $Data);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['message' => __('validation.confirmed', ['attribute' => __('validation.attributes.password')])]);
    }

    public function test_length_password()
    {
        $Data = [
            'email' => $this->email,
            'token' => $this->token,
            'password' => 'Wrong1',
            'password_confirmation' => 'Wrong1',
        ];
        $response = $this->postJson($this->reset_api_url, $Data);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'message' => __('validation.min.string', ['attribute' => __('validation.attributes.password'), 'min' => '8']),
            ]);
    }

    public function test_mixed_case_password()
    {
        $Data = [
            'email' => $this->email,
            'token' => $this->token,
            'password' => 'wrongpassword1',
            'password_confirmation' => 'wrongpassword1',
        ];
        $response = $this->postJson($this->reset_api_url, $Data);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'message' => __('validation.password.mixed', ['attribute' => __('validation.attributes.password')]),
            ]);
    }

    public function test_number_password()
    {
        $Data = [
            'email' => $this->email,
            'token' => $this->token,
            'password' => 'WrongPassword',
            'password_confirmation' => 'WrongPassword',
        ];
        $response = $this->postJson($this->reset_api_url, $Data);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['message' => __('validation.password.numbers', ['attribute' => __('validation.attributes.password')])]);
    }

    public function test_successful_reset()
    {
        $token = Password::createToken($this->user);

        $response = $this->postJson($this->reset_api_url, [
            'email' => $this->email,
            'token' => $token,
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['msg' => __('passwords.reset')]);
    }

    public function test_reset_password_expired_token()
    {
        DB::table('password_resets')->where(['email' => $this->email])->delete();
        $Data = [
            'email' => $this->email,
            'token' => $this->token,
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ];
        $response = $this->postJson($this->reset_api_url, $Data);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['message' => __('passwords.token')]);
    }
}
