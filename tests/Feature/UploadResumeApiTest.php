<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UploadResumeApiTest extends TestCase
{
    use RefreshDatabase;

    private string $api_url;

    private mixed $file;

    private mixed $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->api_url = 'api/v1/personal/resume';
        $this->file = UploadedFile::fake()->create('resume', 3072, 'application/pdf');

        $this->user = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'testuser@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Smith123456'),
            'remember_token' => Str::random(10),
        ]);
    }

    public function test_upload_resume()
    {
        $this->actingAs($this->user);
        $response = $this->postJson($this->api_url, ['resume' => $this->file]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'path',
                ],
            ]);
    }

    public function test_incorrect_ext_file()
    {
        $this->actingAs($this->user);
        $file = UploadedFile::fake()->create('resume', 3072, 'image/jpeg');
        $response = $this->postJson($this->api_url, ['resume' => $file]);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['message' => __('validation.mimetypes', ['attribute' => 'resume', 'values' => 'doc, pdf, docx, zip'])]);
    }

    public function test_max_resume_size()
    {
        $this->actingAs($this->user);
        $file = UploadedFile::fake()->create('resume', 10000, 'application/pdf');
        $response = $this->postJson($this->api_url, ['resume' => $file]);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['message' => __('validation.max.file', ['attribute' => 'resume', 'max' => 5120])]);
    }

    public function test_unauthorized_add_resume()
    {
        $file = UploadedFile::fake()->create('resume', 10000, 'application/pdf');
        $response = $this->postJson($this->api_url, ['resume' => $file]);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['error' => __('authorize.unauthorized')]);
    }
}
