<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'report' => fake()->paragraph(),
            'user_id' => User::factory(),
            'reported_user_id' => User::factory(),
            'type' => fake()->randomElement([
                'spam',
                'harassment',
                'inappropriate',
                'copyright',
                'misinformation',
            ]),
            'priority' => fake()->randomElement([
                'low',
                'medium',
                'high',
            ]),
            'status' => fake()->randomElement([
                'pending',
                'in_review',
                'resolved',
                'dismissed',
            ]),
            'moderator_notes' => fake()->optional()->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
