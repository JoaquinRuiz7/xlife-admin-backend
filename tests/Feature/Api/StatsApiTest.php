<?php

namespace Feature\Api;

use App\Enums\ReportStatus;
use App\Models\Country;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatsApiTest extends TestCase
{

    use RefreshDatabase;

    public function test_overview_stats(): void
    {
        $country = Country::factory()->create();

        $users = User::factory()
            ->count(3)
            ->create([
                'status' => 'active',
                'country_id' => $country->id,
            ]);

        Report::factory()
            ->count(10)
            ->create([
                'status' => ReportStatus::PENDING,
                'user_id' => fn() => $users->random()->id,
                'reported_user_id' => fn(array $attributes) => $users
                    ->where('id', '!=', $attributes['user_id'])
                    ->random()
                    ->id,
            ]);

        $response = $this->get('/api/stats/overview');
        $response->assertOk();
        $response->assertExactJson([
            'totalUsers' => 3,
            'activeUsers' => 3,
            'pendingReports' => 10,
        ]);
    }
}
