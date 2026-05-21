<?php

namespace Database\Seeders;

use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class SecurityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()->get();

        SecurityLog::factory()
            ->count(100)
            ->create([
                'user_id' => fn () => $users->random()->id,
            ]);
    }
}
