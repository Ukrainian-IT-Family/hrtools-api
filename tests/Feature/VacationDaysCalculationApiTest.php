<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\VacationOrHospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

final class VacationDaysCalculationApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo(Carbon::parse('2026-11-17 10:30:00'));

        $this->user = User::factory()->create(['hire_date' => '2022-03-04']);
    }

    public function test_info_counts_vacation_days_of_current_work_year(): void
    {
        $this->createRequest(VacationOrHospital::ACCEPT_STATUS, '2026-03-14', 3);
        $this->createRequest(VacationOrHospital::PENDING_STATUS, '2026-03-24', 2);
        $this->createRequest(VacationOrHospital::CANCEL_STATUS, '2026-04-03', 5);
        $this->createRequest(VacationOrHospital::ACCEPT_STATUS, '2026-02-22', 7);

        $this->actingAs($this->user)
            ->getJson('api/v1/vacation/info')
            ->assertStatus(200)
            ->assertJsonPath('data.availableVacationsDays', 13)
            ->assertJsonPath('data.vacationDaysUsed', 3);
    }

    public function test_create_vacation_request_stores_whole_calendar_days(): void
    {
        $this->actingAs($this->user)
            ->postJson('api/v1/vacation', [
                'type' => VacationOrHospital::VACATION_ID,
                'dateStart' => '2026-11-20',
                'dateEnd' => '2026-11-30',
            ])
            ->assertStatus(200)
            ->assertJsonPath('data.daysCount', 10);

        $this->assertDatabaseHas('vacation_or_hospitals', [
            'user_id' => $this->user->getId(),
            'days_count' => 10,
        ]);
    }

    public function test_create_hospital_request_is_not_limited_by_available_vacation_days(): void
    {
        $this->actingAs($this->user)
            ->postJson('api/v1/vacation', [
                'type' => VacationOrHospital::HOSPITAL_ID,
                'dateStart' => '2026-11-20',
                'dateEnd' => '2026-12-20',
            ])
            ->assertStatus(200)
            ->assertJsonPath('data.daysCount', 30);
    }

    private function createRequest(?int $status, string $dateStart, int $daysCount): void
    {
        VacationOrHospital::create([
            'user_id' => $this->user->getId(),
            'type' => VacationOrHospital::VACATION_ID,
            'date_start' => $dateStart,
            'date_end' => Carbon::parse($dateStart)->addDays($daysCount)->format('Y-m-d'),
            'days_count' => $daysCount,
            'status' => $status,
        ]);
    }
}
