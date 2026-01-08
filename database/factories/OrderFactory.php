<?php

namespace Database\Factories;

use App\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'total' => $this->faker->numberBetween(100, 5000),
            'status' => OrderStatus::PENDING->value,
        ];
    }
}
