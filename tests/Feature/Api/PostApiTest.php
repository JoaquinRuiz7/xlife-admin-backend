<?php

namespace Feature\Api;

use App\Models\Country;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_fetch_posts(): void
    {
        $country = Country::factory()->create();
        $users = User::factory()->count(3)->create([
            'country_id' => $country->id,
        ]);
        $users->each(function (User $user) {
            Post::factory()
                ->count(3)
                ->for($user)
                ->create();
        });
        $response = $this->getJson('/api/posts');
        $response->assertJsonPath('meta.total', 9);
        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'content',
                        'createdBy',
                        'status',
                        'created',
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

    public function test_it_validates_page_size_max_value(): void
    {
        $response = $this->getJson('/api/posts?pageSize=150');
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['pageSize']);
    }
}
