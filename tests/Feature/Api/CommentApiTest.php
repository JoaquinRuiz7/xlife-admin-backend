<?php

namespace Feature\Api;

use App\Models\Country;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_get_comments(): void
    {
        $country = Country::factory()->create();

        $users = User::factory()
            ->count(3)
            ->create([
                'country_id' => $country->id,
            ]);

        $posts = collect();

        $users->each(function (User $user) use ($posts) {
            $createdPosts = Post::factory()
                ->count(3)
                ->for($user)
                ->create();

            $posts->push(...$createdPosts);
        });

        $posts->each(function (Post $post) use ($users) {
            PostComment::factory()
                ->count(3)
                ->for($post)
                ->for($users->random())
                ->create();
        });

        $response = $this->getJson('/api/comments');
        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'commentedBy',
                        'comment',
                        'post',
                        'status'
                    ],
                ],
                'meta' => [
                    'total',
                    'page',
                    'pageSize',
                    'lastPage',
                ],
            ])
            ->assertJsonPath('meta.total', 27);
    }
}
