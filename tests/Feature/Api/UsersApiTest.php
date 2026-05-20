<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_paginated_users(): void
    {
        User::factory()->count(3)->create();
        $response = $this->getJson('/api/users');
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
        User::factory()->count(20)->create();

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
        ]);

        User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);

        $response = $this->getJson('/api/users?search=John');
        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'John Doe');
    }

    public function test_get_user_by_id(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
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
            ]);
    }

    public function test_user_not_found_exception(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $id = $user->id + 1;
        $response = $this->getJson("/api/users/$id");

        $response
            ->assertStatus(404)
            ->assertJson(['message' => 'User not found.']);
    }
}
