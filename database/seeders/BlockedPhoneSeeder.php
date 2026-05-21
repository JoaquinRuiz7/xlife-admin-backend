<?php

namespace Database\Seeders;

use App\Models\BlockedPhone;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlockedPhoneSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->get();

        BlockedPhone::factory()
            ->count(15)
            ->create([
                'user_id' => fn () => $users->random()->id,
            ]);
    }
}
