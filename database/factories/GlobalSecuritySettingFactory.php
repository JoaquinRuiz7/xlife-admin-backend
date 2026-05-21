<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GlobalSecuritySetting>
 */
class GlobalSecuritySettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Auto-block Suspicious IPs',
                'Two-Factor Authentication',
                'Rate Limiting',
                'Activity Logging',
            ]),
            'active' => fake()->randomElement([true, false]),
        ];
    }
}
