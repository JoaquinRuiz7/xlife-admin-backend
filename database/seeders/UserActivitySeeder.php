<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Database\Seeder;

class UserActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::query()->pluck('id');

        UserActivity::factory()
            ->count(1000)
            ->create([
                'user_id' => fn() => $userIds->random(),
            ]);
    }
}
