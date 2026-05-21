<?php

namespace Database\Factories;

use App\Models\BlockedPhone;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BlockedPhone>
 */
class BlockedPhoneFactory extends Factory
{
    protected $model = BlockedPhone::class;

    public function definition(): array
    {
        return [
            'phone_number' => fake()->unique()->phoneNumber(),
            'user_id' => User::factory(),
        ];
    }
}
