<?php

namespace Feature\Api;

use App\Enums\ReportStatus;
use App\Models\Country;
use App\Models\Post;
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

    public function test__get_geographic_distribution(): void
    {
        $uruguay = Country::factory()->create([
            'name' => 'Uruguay',
        ]);

        $italy = Country::factory()->create([
            'name' => 'Italy',
        ]);

        User::factory()
            ->count(3)
            ->create([
                'status' => 'active',
                'country_id' => $uruguay->id,
            ]);

        User::factory()
            ->count(2)
            ->create([
                'status' => 'active',
                'country_id' => $italy->id,
            ]);

        $response = $this->get('/api/stats/geographic-distribution');

        $response->assertOk();

        $response->assertJsonFragment([
            'country' => 'Uruguay',
            'count' => 3,
        ]);

        $response->assertJsonFragment([
            'country' => 'Italy',
            'count' => 2,
        ]);
    }

    public function test_get_viral_posts(): void
    {
        $country = Country::factory()->create();

        $user = User::factory()->create([
            'country_id' => $country->id,
        ]);

        $lowScorePost = Post::factory()->create([
            'user_id' => $user->id,
            'views' => 100,
            'likes' => 10,
            'shares' => 1,
        ]);
        // (10 * 2) + (1 * 5) + 100
        // 20 + 5 + 100 = 125/10 = 12.5


        $highScorePost = Post::factory()->create([
            'user_id' => $user->id,
            'views' => 1000,
            'likes' => 100,
            'shares' => 20,
        ]);
        // (100 * 2) + (20 * 5) + 1000
        // 200 + 100 + 1000 = 1300 / 10 = 130
        $response = $this->get('/api/stats/viral-posts');

        $response->assertOk();

        $response->assertJsonPath('0.id', $highScorePost->id);
        $response->assertJsonPath('1.id', $lowScorePost->id);

        $response->assertJsonPath('0.viral_score', 130);
        $response->assertJsonPath('1.viral_score', 12.5);
    }

}
