<?php

namespace Database\Factories;

use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SecurityLog>
 */
class SecurityLogFactory extends Factory
{
    protected $model = SecurityLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'ip_address' => $this->faker->ipv4(),
            'event' => fake()->randomElement([
                'failed_login_attempt',
                'ip_block_automatically',
                'password_reset_required',
                'suspicious_activity',
                'new_device_login',
                'account_lockout',
            ]),
            'severity' => fake()->randomElement([
                'info',
                'warning',
                'high',
            ]),
            'created_at' => now()
                ->subDays(fake()->numberBetween(0, 60))
                ->subMinutes(fake()->numberBetween(0, 1440)),
            'updated_at' => now(),
        ];
    }
}
