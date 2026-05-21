<?php

namespace Feature\Api;

use App\Models\Country;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_reports(): void
    {
        $country = Country::factory()->create();

        $users = User::factory()
            ->count(3)
            ->create([
                'country_id' => $country->id,
            ]);

        Report::factory()
            ->count(10)
            ->create([
                'user_id' => fn() => $users->random()->id,
                'reported_user_id' => fn(array $attributes) => $users
                    ->where('id', '!=', $attributes['user_id'])
                    ->random()
                    ->id,
            ]);

        $response = $this->getJson('/api/reports');

        $response
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.total', 10);
    }

    public function test_get_reports_with_report_count_for_reported(): void
    {
        $country = Country::factory()->create();

        $users = User::factory()
            ->count(3)
            ->create([
                'country_id' => $country->id,
            ]);

        Report::factory()
            ->count(10)
            ->create([
                'user_id' => fn() => $users->random()->id,
                'reported_user_id' => fn(array $attributes) => $users
                    ->where('id', '!=', $attributes['user_id'])
                    ->random()
                    ->id,
            ]);

        $response = $this->getJson('/api/reports?withReportCountForReportedUser=1');
        $response->assertJsonPath('data.0.reportedUserTotalReports', fn($value) => !is_null($value));
        $response
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.total', 10);
    }
}
