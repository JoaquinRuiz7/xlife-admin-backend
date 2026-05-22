<?php

namespace Tests\Feature\Api;

use App\Models\Country;
use App\Models\Post;
use App\Models\User;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_paginated_users(): void
    {

        $country = Country::factory()->create();
        User::factory()->count(3)->create([
            'country_id' => $country->id,
        ]);
        $response = $this->getJson('/api/users?withLastIp=1');
        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'cellphone',
                        'country',
                        'lastActive',
                        'lastKnownIp',
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

    public function test_it_accepts_page_size_query_param(): void
    {
        $country = Country::factory()->create();
        User::factory()->count(20)->create([
            'country_id' => $country->id,
        ]);

        $response = $this->getJson('/api/users?pageSize=5');

        $response
            ->assertOk()
            ->assertJsonPath('meta.pageSize', 5)
            ->assertJsonPath('meta.total', 20)
            ->assertJsonPath('meta.lastPage', 4)
            ->assertJsonCount(5, 'data');
    }

    public function test_it_validates_page_size_max_value(): void
    {
        $response = $this->getJson('/api/users?pageSize=150');
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['pageSize']);
    }

    public function test_search_by_name(): void
    {
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'country_id' => Country::factory()->create()->id,
        ]);

        User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'country_id' => Country::factory()->create()->id,
        ]);

        $response = $this->getJson('/api/users?search=John');
        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'John Doe');
    }

    public function test_get_user_by_id(): void
    {
        $country = Country::factory()->create();
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'country_id' => $country->id,
        ]);

        $response = $this->getJson("/api/users/$user->id");
        $response
            ->assertOk()
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'cellphone' => $user->cellphone,
                'country' => $user->country->name,
                'registered' => $user->created_at->toISOString(),
                'lastActive' => $user->last_login_at?->diffForHumans(),
            ]);
    }

    public function test_user_not_found_exception(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'country_id' => Country::factory()->create()->id,
        ]);

        $id = $user->id + 1;
        $response = $this->getJson("/api/users/$id");

        $response
            ->assertStatus(404)
            ->assertJson(['message' => 'User not found.']);
    }

    public function test_get_user_posts(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'country_id' => Country::factory()->create()->id,
        ]);

        Post::factory()
            ->count(3)
            ->for($user)
            ->create();

        $response = $this->getJson("/api/users/{$user->id}/posts");

        $response
            ->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJson([
                'meta' => [
                    'total' => 3,
                    'page' => 1,
                    'pageSize' => 25,
                    'lastPage' => 1,
                ],
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user',
                        'content',
                        'created_at',
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

    public function test_get_user_activity(): void
    {
        Carbon::setTestNow('2026-05-20 00:00:00');

        $user = User::factory()->create([
            'country_id' => Country::factory()->create()->id,
        ]);

        UserActivity::factory()->create([
            'user_id' => $user->id,
            'started_at' => Carbon::parse('2026-05-20 10:34:00'),
            'ended_at' => Carbon::parse('2026-05-20 11:35:00'),
        ]);

        $response = $this->getJson("/api/users/{$user->id}/activity");

        $expected = collect(range(0, 23))
            ->map(fn(int $hour) => [
                'hour' => $hour,
                'minutes' => match ($hour) {
                    10 => 26,
                    11 => 35,
                    default => 0,
                },
            ])
            ->toArray();

        $response
            ->assertStatus(200)
            ->assertExactJson($expected);

        Carbon::setTestNow();
    }
}
