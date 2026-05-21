<?php

namespace Tests\Feature\Api;

use App\Models\BlockedIp;
use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlockedIpsTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_blocked_ips(): void
    {
        $countries = Country::factory()
            ->count(3)
            ->create();

        BlockedIp::factory()
            ->count(25)
            ->create([
                'country_id' => fn () => $countries->random()->id,
            ]);

        $response = $this->getJson('/api/security/blocked-ips');

        $response
            ->assertOk()
            ->assertJsonCount(25, 'data')
            ->assertJsonPath('meta.total', 25)
            ->assertJsonPath('meta.page', 1)
            ->assertJsonPath('meta.pageSize', 25)
            ->assertJsonPath('meta.lastPage', 1)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'ip',
                        'country',
                        'blockedAt',
                        'reason',
                    ],
                ],
                'meta' => [
                    'total',
                    'page',
                    'pageSize',
                    'lastPage',
                ],
            ]);
    }
}
