<?php

namespace Tests\Feature;

use App\Models\Resume;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class DeleteResumeByAdminApiTest extends TestCase
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
            'email' => 'testuser@example.com',
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
        $resumePath = Storage::disk('s3')->putFileAs(
            Config::get('filesystems.resumes_dir'),
            $this->file,
            $this->file->hashName(),
            's3'
        );

        Resume::factory()->create(
            [
                'name' => $this->file->hashName(),
                'path' => Storage::disk('s3')->url($resumePath),
                'user_id' => $this->user->id,
            ]
        );
    }

    public function test_delete_resume_success()
    {
        $this->admin->role_id = User::ADMIN_ROLE_ID;
        $this->actingAs($this->admin);

        $this->assertDatabaseHas('resumes', [
            'user_id' => $this->user->id,
        ]);

        $this->deleteJson($this->api_url);

        $this->assertDatabaseMissing('resumes', [
            'user_id' => $this->user->id,
        ]);
    }

    public function test_unauthorized_user_delete_resume()
    {
        $response = $this->deleteJson($this->api_url);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['error' => __('authorize.unauthorized')]);
    }

    public function test_forbidden_by_role_delete_resume()
    {
        $this->actingAs($this->user);
        $response = $this->deleteJson($this->api_url);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['error' => __('authorize.forbidden_by_role')]);
    }
}
