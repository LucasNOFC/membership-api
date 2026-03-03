<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Básico',
                'price' => 100,
                'duration_days' => 30,
                'is_active' => true,
                'description' => 'Básico',
            ],
            [
                'name' => 'Intermediário',
                'price' => 125,
                'duration_days' => 30,
                'is_active' => true,
                'description' => 'Intermediário',
            ],
            [
                'name' => 'Profissional',
                'price' => 150,
                'duration_days' => 30,
                'is_active' => true,
                'description' => 'Profissional',
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}