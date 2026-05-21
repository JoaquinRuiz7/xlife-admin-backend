<?php

namespace Feature\Api;

use App\Models\Country;
use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityLogsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $country = Country::factory()->create();

        $users = User::factory()
            ->count(3)
            ->create([
                'country_id' => $country->id,
            ]);

        SecurityLog::factory()
            ->count(10)
            ->create([
                'user_id' => fn() => $users->random()->id,
            ]);

        $response = $this->getJson('/api/security/logs');

        $response
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'severity',
                        'ipAddress',
                        'userId',
                        'event',
                    ],
                ],
            ])
            ->assertJsonPath('meta.total', 10);
    }
}
