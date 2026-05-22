<?php

namespace Database\Factories;

use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SessionFactory extends Factory
{
    protected $model = Session::class;

    public function definition(): array
    {
        return [
            'id' => Str::random(40),
            'user_id' => User::query()->inRandomOrder()->value('id'),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'payload' => base64_encode(serialize([])),
            'last_activity' => now()
                ->subDays($this->faker->numberBetween(0, 27))
                ->subMinutes($this->faker->numberBetween(0, 1440))
                ->timestamp,
        ];
    }

    public function guest(): static
    {
        return $this->state(fn () => [
            'user_id' => null,
        ]);
    }
}
