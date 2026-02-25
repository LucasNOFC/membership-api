<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Mensal', 'Trimestral', 'Anual']),
            'price' => fake()->randomFloat(2, 50, 200),
            'duration_days' => fake()->randomElement([30, 90, 365]),
            'is_active' => true,
            'description' => fake()->sentence(),
        ];
    }
}
