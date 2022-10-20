<?php

namespace Tests\Feature;

use App\Models\MaritalStatus;
use App\Models\Relationship;
use App\Models\User;
use Database\Seeders\MaritalStatusSeeder;
use Database\Seeders\RelationshipSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdateProfileApiTest extends TestCase
{
    use RefreshDatabase;

    private string $api_url;

    private string $first_name;

    private string $middle_name;

    private string $last_name;

    private string $birthday;

    private bool $gender;

    private int $marital_status;

    private array $children;

    private string $region;

    private string $area;

    private string $town;

    private string $post_office;

    private array $phones;

    private string $email;

    private string $linkedin;

    private string $facebook;

    private array $emergency_contact;

    private mixed $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->api_url = 'api/v1/personal/me';

        $this->first_name = 'First';
        $this->middle_name = 'Middle';
        $this->last_name = 'Last';
        $this->birthday = '2000-02-02';
        $this->gender = true;
        $this->marital_status = 2;
        $this->children = [['fullName' => 'Child 1', 'gender' => false, 'birthday' => ' 2021-01-01']];
        $this->region = 'Київська';
        $this->area = 'Дарницький';
        $this->town = 'Київ';
        $this->post_office = 'відділення';
        $this->phones = [['phone' => '174079274561'], ['phone' => '174079274563']];
        $this->email = 'updateUser@example.com';
        $this->linkedin = 'linkedin.com';
        $this->facebook = 'facebook.com';
        $this->emergency_contact = [['fullName' => 'Contact mom', 'relationship' => 2, 'emergencyPhones' => [['phone' => '174079274565']]]];

        $this->user = User::factory()->create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => Hash::make('Smith123456'),
        ]);
    }

    public function test_required_fields_for_update_profile()
    {
        $this->actingAs($this->user);
        $response = $this->putJson($this->api_url, []);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'errors' => [
                    'firstName' => [__('validation.required', ['attribute' => 'first name'])],
                    'middleName' => [__('validation.required', ['attribute' => 'middle name'])],
                    'lastName' => [__('validation.required', ['attribute' => 'last name'])],
                    'birthday' => [__('validation.required', ['attribute' => 'birthday'])],
                    'gender' => [__('validation.required', ['attribute' => 'gender'])],
                    'maritalStatus' => [__('validation.required', ['attribute' => 'marital status'])],
                    'region' => [__('validation.required', ['attribute' => 'region'])],
                    'area' => [__('validation.required', ['attribute' => 'area'])],
                    'town' => [__('validation.required', ['attribute' => 'town'])],
                    'postOffice' => [__('validation.required', ['attribute' => 'post office'])],
                    'contactsPhones' => [__('validation.required', ['attribute' => 'contacts phones'])],
                    'email' => [__('validation.required', ['attribute' => __('validation.attributes.email')])],
                    'emergency' => [__('validation.required', ['attribute' => 'emergency'])],
                ],
            ]);
    }

    public function test_email_already_taken()
    {
        $this->seed([RelationshipSeeder::class, RoleSeeder::class, MaritalStatusSeeder::class]);
        $this->actingAs($this->user);
        $email = 'duplicate@example.com';
        User::factory()->create([
            'first_name' => 'Repeat',
            'last_name' => 'Email',
            'email' => $email,
            'password' => Hash::make('Smith123456'),
        ]);
        $userData = [
            'firstName' => $this->first_name,
            'middleName' => $this->middle_name,
            'lastName' => $this->last_name,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'maritalStatus' => $this->marital_status,
            'children' => $this->children,
            'region' => $this->region,
            'area' => $this->area,
            'town' => $this->town,
            'postOffice' => $this->post_office,
            'contactsPhones' => $this->phones,
            'email' => $email,
            'linkedin' => $this->linkedin,
            'facebook' => $this->facebook,
            'emergency' => $this->emergency_contact,
        ];

        $response = $this->putJson($this->api_url, $userData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['email' => [__('validation.unique', ['attribute' => __('validation.attributes.email')])]]);
    }

    public function test_birthday_before_tomorrow()
    {
        $this->seed([RelationshipSeeder::class, RoleSeeder::class, MaritalStatusSeeder::class]);
        $this->actingAs($this->user);
        $userData = [
            'firstName' => $this->first_name,
            'middleName' => $this->middle_name,
            'lastName' => $this->last_name,
            'birthday' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'gender' => $this->gender,
            'maritalStatus' => $this->marital_status,
            'children' => $this->children,
            'region' => $this->region,
            'area' => $this->area,
            'town' => $this->town,
            'postOffice' => $this->post_office,
            'contactsPhones' => $this->phones,
            'email' => $this->email,
            'linkedin' => $this->linkedin,
            'facebook' => $this->facebook,
            'emergency' => $this->emergency_contact,
        ];

        $response = $this->putJson($this->api_url, $userData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['birthday' => [__('validation.before', ['attribute' => 'birthday', 'date' => 'tomorrow'])]]);
    }

    public function test_incorrect_contact_phone()
    {
        $this->seed([RelationshipSeeder::class, RoleSeeder::class, MaritalStatusSeeder::class]);
        $this->actingAs($this->user);

        $userData = [
            'firstName' => $this->first_name,
            'middleName' => $this->middle_name,
            'lastName' => $this->last_name,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'maritalStatus' => $this->marital_status,
            'children' => $this->children,
            'region' => $this->region,
            'area' => $this->area,
            'town' => $this->town,
            'postOffice' => $this->post_office,
            'contactsPhones' => [['phone' => '338fshhe12']],
            'email' => $this->email,
            'linkedin' => $this->linkedin,
            'facebook' => $this->facebook,
            'emergency' => $this->emergency_contact,
        ];

        $response = $this->putJson($this->api_url, $userData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['contactsPhones.0.phone' => [__('validation.regex', ['attribute' => 'contactsPhones.0.phone'])]]);
    }

    public function test_contact_phones_is_array()
    {
        $this->seed([RelationshipSeeder::class, RoleSeeder::class, MaritalStatusSeeder::class]);
        $this->actingAs($this->user);

        $userData = [
            'firstName' => $this->first_name,
            'middleName' => $this->middle_name,
            'lastName' => $this->last_name,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'maritalStatus' => $this->marital_status,
            'children' => $this->children,
            'region' => $this->region,
            'area' => $this->area,
            'town' => $this->town,
            'postOffice' => $this->post_office,
            'contactsPhones' => 'string',
            'email' => $this->email,
            'linkedin' => $this->linkedin,
            'facebook' => $this->facebook,
            'emergency' => $this->emergency_contact,
        ];

        $response = $this->putJson($this->api_url, $userData, ['Accept-Language' => 'ua']);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['errors' => [
                'contactsPhones' => [__('validation.array', ['attribute' => 'contacts phones', 'min' => '1'])],
            ]]);
    }

    public function test_emergency_contacts_is_array()
    {
        $this->seed([RelationshipSeeder::class, RoleSeeder::class, MaritalStatusSeeder::class]);
        $this->actingAs($this->user);

        $userData = [
            'firstName' => $this->first_name,
            'middleName' => $this->middle_name,
            'lastName' => $this->last_name,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'maritalStatus' => $this->marital_status,
            'children' => $this->children,
            'region' => $this->region,
            'area' => $this->area,
            'town' => $this->town,
            'postOffice' => $this->post_office,
            'contactsPhones' => $this->phones,
            'email' => $this->email,
            'linkedin' => $this->linkedin,
            'facebook' => $this->facebook,
            'emergency' => 'string',
        ];

        $response = $this->putJson($this->api_url, $userData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['emergency' => [__('validation.array', ['attribute' => 'emergency'])]]);
    }

    public function test_gender_incorrect()
    {
        $this->seed([RelationshipSeeder::class, RoleSeeder::class, MaritalStatusSeeder::class]);
        $this->actingAs($this->user);

        $userData = [
            'firstName' => $this->first_name,
            'middleName' => $this->middle_name,
            'lastName' => $this->last_name,
            'birthday' => $this->birthday,
            'gender' => 5,
            'maritalStatus' => $this->marital_status,
            'children' => $this->children,
            'region' => $this->region,
            'area' => $this->area,
            'town' => $this->town,
            'postOffice' => $this->post_office,
            'contactsPhones' => $this->phones,
            'email' => $this->email,
            'linkedin' => $this->linkedin,
            'facebook' => $this->facebook,
            'emergency' => $this->emergency_contact,
        ];

        $response = $this->putJson($this->api_url, $userData);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'errors' => [
                    'gender' => [__('validation.boolean', ['attribute' => 'gender'])],
                ],
            ]);
    }

    public function test_update_profile_success()
    {
        $this->actingAs($this->user);
        $this->seed([MaritalStatusSeeder::class, RelationshipSeeder::class, RoleSeeder::class]);
        $relation = Relationship::query()->first();
        $marital_status = MaritalStatus::query()->first();

        $userData = [
            'firstName' => $this->first_name,
            'middleName' => $this->middle_name,
            'lastName' => $this->last_name,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'maritalStatus' => $marital_status->id,
            'children' => $this->children,
            'region' => $this->region,
            'area' => $this->area,
            'town' => $this->town,
            'postOffice' => $this->post_office,
            'contactsPhones' => $this->phones,
            'email' => $this->email,
            'linkedin' => $this->linkedin,
            'facebook' => $this->facebook,
            'emergency' => [['fullName' => 'Contact', 'relationship' => $relation->id, 'emergencyPhones' => [['phone' => '174079274565']]]],
        ];

        $response = $this->putJson($this->api_url, $userData);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
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
            ]);
    }
}
