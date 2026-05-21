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
                'password_reset_requested',
                'password_reset_completed',
                'user_blocked',
                'two_factor_enabled',
                'two_factor_disabled',
                'permission_denied',
                'suspicious_activity',
            ]),
            'source' => fake()->randomElement([
                'admin_panel', 'api', 'auth',
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
