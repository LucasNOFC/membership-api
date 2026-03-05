<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paidAt = fake()->dateTimeBetween(
            now()->startOfMonth(),
            now()->endOfMonth()
        );

        return [
            'member_id' => Member::factory(),
            'amount' => fake()->randomFloat(2, 50, 200),
            'reference_month' => now()->format('Y-m'),
            'paid_at' => $paidAt,
        ];
    }
}
