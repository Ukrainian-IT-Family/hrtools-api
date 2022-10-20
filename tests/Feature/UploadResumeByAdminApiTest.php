<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UploadResumeByAdminApiTest extends TestCase
{
    use RefreshDatabase;

    private string $api_url;

    private mixed $file;

    private mixed $user;

    private mixed $admin;

    protected $seeder = RoleSeeder::class;

    public function setUp(): void
    {
        parent::setUp();
        $this->file = UploadedFile::fake()->create('resume', 3072, 'application/pdf');
        $this->user = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'testuser2@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Smith123456'),
            'remember_token' => Str::random(10),
        ]);
        $this->admin = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Admin',
            'email' => 'admin0@example.com',
            'password' => Hash::make('Admin123456'),
            'email_verified_at' => now(),
        ]);
        $this->api_url = 'api/v1/admin/users/'.$this->user->id.'/resume';
    }

    public function test_upload_resume()
    {
        $this->admin->role_id = User::ADMIN_ROLE_ID;
        $this->actingAs($this->admin);
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
        $this->admin->role_id = User::ADMIN_ROLE_ID;
        $this->actingAs($this->admin);
        $file = UploadedFile::fake()->create('resume', 3072, 'image/jpeg');
        $response = $this->postJson($this->api_url, ['resume' => $file]);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['message' => __('validation.mimetypes', ['attribute' => 'resume', 'values' => 'doc, pdf, docx, zip'])]);
    }

    public function test_max_resume_size()
    {
        $this->admin->role_id = User::ADMIN_ROLE_ID;
        $this->actingAs($this->admin);
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

    public function test_uploaded_resume_not_by_admin()
    {
        $this->actingAs($this->user);
        $file = UploadedFile::fake()->create('resume', 10000, 'application/pdf');
        $response = $this->postJson($this->api_url, ['resume' => $file]);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['error' => __('authorize.forbidden_by_role')]);
    }
}
