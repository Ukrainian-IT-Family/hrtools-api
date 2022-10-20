<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SwitchLangApiTest extends TestCase
{
    use RefreshDatabase;

    private string $api_url;

    private string $email;

    private string $password;

    private string $default_lang;

    private string $lang;

    private mixed $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->api_url = '/api/v1/auth/login';
        $this->email = 'test@example.com';
        $this->password = 'Ye4oKoEa3Ro9llC';
        $this->default_lang = 'ua';
        $this->lang = 'en';
        $this->user = User::factory()->create([
            'first_name' => 'Test2',
            'last_name' => 'User',
            'email' => $this->email,
            'password' => \Hash::make($this->password),
        ]);
    }

    public function test_switch_locale_fail()
    {
        \App::setLocale($this->default_lang);
        $this->postJson($this->api_url, [
            'email' => $this->email,
            'password' => $this->password,
        ], ['Accept-Language' => '']);

        $this->assertTrue($this->lang !== \App::getLocale() &&
                          $this->default_lang === \App::getLocale()
        );
    }

    public function test_switch_locale_success()
    {
        \App::setLocale($this->default_lang);
        $this->postJson($this->api_url, [
            'email' => $this->email,
            'password' => $this->password,
        ], ['Accept-Language' => $this->lang]);

        $this->assertTrue($this->lang === \App::getLocale());
    }
}
