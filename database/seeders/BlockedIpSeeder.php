<?php

namespace Database\Seeders;

use App\Models\BlockedIp;
use App\Models\Country;
use Illuminate\Database\Seeder;

class BlockedIpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = Country::query()->get();
        BlockedIp::factory()
            ->count(100)
            ->create([
                'country_id' => fn () => $countries->random()->id,
            ]);
    }
}
