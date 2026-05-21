<?php

namespace Tests\Feature\Api;

use App\Models\BlockedPhone;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlockedPhoneTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_blocked_phones(): void
    {
        $country = Country::factory()->create();

        $users = User::factory()
            ->count(3)
            ->create([
                'country_id' => $country->id,
            ]);

        BlockedPhone::factory()
            ->count(10)
            ->create([
                'user_id' => fn () => $users->random()->id,
            ]);

        $response = $this->getJson('/api/security/blocked-phones');

        $response
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.total', 10)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'phoneNumber',
                        'user',
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

    public function test_get_blocked_phones_with_search(): void
    {
        $country = Country::factory()->create();

        $user = User::factory()->create([
            'country_id' => $country->id,
        ]);

        BlockedPhone::factory()->create([
            'user_id' => $user->id,
            'phone_number' => '+59899123456',
        ]);

        BlockedPhone::factory()->create([
            'user_id' => $user->id,
            'phone_number' => '+393331234567',
        ]);

        $response = $this->getJson('/api/security/blocked-phones?phone=+598');

        $response
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.phoneNumber', '+59899123456');
    }
}
