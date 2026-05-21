<?php

namespace Database\Factories;

use App\Models\BlockedIp;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BlockedIpFactory extends Factory
{
    protected $model = BlockedIp::class;

    public function definition(): array
    {
        return [
            'ip_address' => fake()->ipv4(),

            'reason' => fake()->randomElement([
                'Too many failed login attempts',
                'Suspicious activity detected',
                'Permission abuse',
                'Automated requests detected',
                'Fraud prevention',
            ]),

            'country_id' => Country::factory(),

            'blocked_at' => now()
                ->subDays(fake()->numberBetween(0, 60))
                ->subMinutes(fake()->numberBetween(0, 1440)),
        ];
    }
}
