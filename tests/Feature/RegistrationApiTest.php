<?php

namespace Tests\Feature;

use App\Models\Phone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegistrationApiTest extends TestCase
{
    use RefreshDatabase;

    private string $register_api_url;

    private string $resend_url;

    private mixed $user;

    private mixed $user_not_verified;

    private mixed $user_not_verified_two;

    public function setUp(): void
    {
        parent::setUp();

        $this->register_api_url = 'api/v1/auth/register';
        $this->resend_url = 'api/v1/email/resend';

        $this->user = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'testuser@example.com',
            'password' => Hash::make('Smith123456'),
        ]);

        $this->user_not_verified = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'testnotverified@example.com',
            'password' => Hash::make('Smith123456'),
            'email_verified_at' => null,
        ]);

        $this->user_not_verified_two = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'testnotverified2@example.com',
            'password' => Hash::make('Smith123456'),
            'email_verified_at' => null,
        ]);
    }

    public function test_required_fields_for_registration()
    {
        $response = $this->postJson($this->register_api_url, []);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'errors' => [
                    'firstName' => [__('validation.required', ['attribute' => 'first name'])],
                    'lastName' => [__('validation.required', ['attribute' => 'last name'])],
                    'email' => [__('validation.required', ['attribute' => __('validation.attributes.email')])],
                    'phone' => [__('validation.required', ['attribute' => 'phone'])],
                    'password' => [__('validation.required', ['attribute' => __('validation.attributes.password')])],
                ],
            ]);
    }

    public function test_email_already_taken()
    {
        $userData = [
            'firstName' => 'John',
            'lastName' => 'Smith',
            'email' => $this->user->email,
            'phone' => '380951122444',
            'password' => 'Smith123456',
            'password_confirmation' => 'Smith123456',
        ];

        $response = $this->postJson($this->register_api_url, $userData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['email' => [__('validation.unique', ['attribute' => __('validation.attributes.email')])]]);
    }

    public function test_phone_already_taken()
    {
        $phone = '380951122444';
        Phone::factory()->create([
            'phone' => $phone,
            'user_id' => $this->user->id,
        ]);
        $userData = [
            'firstName' => 'John',
            'lastName' => 'Smith',
            'email' => 'john@example.com',
            'phone' => $phone,
            'password' => 'Smith123456',
            'password_confirmation' => 'Smith123456',
        ];

        $response = $this->postJson($this->register_api_url, $userData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['phone' => [__('validation.unique', ['attribute' => 'phone'])]]);
    }

    public function test_repeat_password()
    {
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'john@example.com',
            'phone' => '380951122555',
            'password' => 'Smith123456',
            'password_confirmation' => 'Smith',
        ];

        $response = $this->postJson($this->register_api_url, $userData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['password' => [__('validation.confirmed', ['attribute' => __('validation.attributes.password')])]]);
    }

    public function test_incorrect_password()
    {
        $userData = [
            'firstName' => 'John',
            'lastName' => 'Smith',
            'email' => 'john@example.com',
            'phone' => '380951122555',
            'password' => 'smith',
            'password_confirmation' => 'smith',
        ];

        $response = $this->postJson($this->register_api_url, $userData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'errors' => [
                    'password' => [
                        __('validation.min.string', ['attribute' => __('validation.attributes.password'), 'min' => '8']),
                        __('validation.password.mixed', ['attribute' => __('validation.attributes.password')]),
                        __('validation.password.numbers', ['attribute' => __('validation.attributes.password')]),
                    ],
                ],
            ]);
    }

    public function test_incorrect_phone()
    {
        $userData = [
            'firstName' => 'John',
            'lastName' => 'Smith',
            'email' => 'john@example.com',
            'phone' => Str::random(11),
            'password' => 'Smith123456',
            'password_confirmation' => 'Smith123456',
        ];

        $response = $this->postJson($this->register_api_url, $userData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['phone' => [__('validation.regex', ['attribute' => 'phone'])]]);
    }

    public function test_incorrect_email()
    {
        $userData = [
            'firstName' => 'John',
            'lastName' => 'Smith',
            'email' => 'john-example.com',
            'phone' => '380951122555',
            'password' => 'Smith123456',
            'password_confirmation' => 'Smith123456',
        ];

        $response = $this->postJson($this->register_api_url, $userData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['email' => [__('validation.email', ['attribute' => __('validation.attributes.email')])]]);
    }

    public function test_incorrect_name_and_last_name()
    {
        $userData = [
            'firstName' => 'Jo',
            'lastName' => 'Sm',
            'email' => 'john@example.com',
            'phone' => '380951122555',
            'password' => 'Smith123456',
            'password_confirmation' => 'Smith123456',
        ];

        $response = $this->postJson($this->register_api_url, $userData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'errors' => [
                    'firstName' => [__('validation.min.string', ['attribute' => 'first name', 'min' => 3])],
                    'lastName' => [__('validation.min.string', ['attribute' => 'last name', 'min' => 3])],
                ],
            ]);
    }

    public function test_successful_registration()
    {
        $userData = [
            'firstName' => 'John',
            'lastName' => 'Smith',
            'email' => 'johnsuccess@example.com',
            'phone' => '380951122543',
            'password' => 'Smith123456',
            'password_confirmation' => 'Smith123456',
        ];

        $response = $this->postJson($this->register_api_url, $userData);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        'avatar',
                        'id',
                        'firstName',
                        'middleName',
                        'lastName',
                        'birthday',
                        'gender',
                        'maritalStatus',
                        'children',
                        'region',
                        'area',
                        'town',
                        'postOffice',
                        'email',
                        'contactsPhones',
                        'linkedin',
                        'facebook',
                        'resume',
                        'emergency',
                        'email_verified_at',
                        'role',
                        'workers',
                        'managerId',
                        'manager',
                        'fullName',
                        'shortName',
                        'workTime',
                        'position',
                        'hireDate',
                    ],
                    'access_token',
                    'token_type',
                    'expires_in',
                ],
            ]);
    }

    public function test_resend_email_verify_success()
    {
        $this->actingAs($this->user_not_verified);
        $response = $this->postJson($this->resend_url.'/'.$this->user_not_verified->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'msg' => __('register.email_verification_link_sent_on_your_email'),
                ],
            ]);
    }

    public function test_resend_email_verify_already_verified()
    {
        $this->actingAs($this->user);
        $response = $this->postJson($this->resend_url.'/'.$this->user->id);

        $response
            ->assertStatus(400)
            ->assertJson(
                [
                    'error' => [
                        'message' => __('register.email_already_verified'),
                        'code' => 400,
                    ],
                ]
            );
    }

    public function test_email_verify_expired_url()
    {
        $user = User::find($this->user_not_verified->id);
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(-1),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $parse = parse_url($verifyUrl);
        $parsedUrl = $parse['path'].'?'.$parse['query'];

        $response = $this->postJson($parsedUrl);

        $response
            ->assertStatus(400)
            ->assertJson(
                [
                    'error' => [
                        'message' => __('register.expired_url_provided'),
                        'code' => 401,
                    ],
                ]
            );
    }

    public function test_email_verify_invalid_url()
    {
        $user = User::find($this->user_not_verified->id);
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $parse = parse_url($verifyUrl);
        $parsedUrl = $parse['path'].'?'.$parse['query'];

        $response = $this->postJson(substr($parsedUrl, 0, -1));

        $response
            ->assertStatus(400)
            ->assertJson(
                [
                    'error' => [
                        'message' => __('register.invalid_url_provided'),
                        'code' => 401,
                    ],
                ]
            );
    }

    public function test_email_verify_success()
    {
        $user = User::find($this->user_not_verified_two->id);
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $parse = parse_url($verifyUrl);
        $parsedUrl = $parse['path'].'?'.$parse['query'];

        $response = $this->postJson($parsedUrl);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'msg' => __('register.user_successfully_verified'),
                ],
            ]);
    }
}
