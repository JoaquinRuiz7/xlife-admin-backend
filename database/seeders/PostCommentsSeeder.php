<?php

namespace Database\Seeders;

use App\Models\PostComment;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostCommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()->get();
        PostComment::factory()
            ->count(1000)
            ->recycle($users)
            ->create();
    }
}
