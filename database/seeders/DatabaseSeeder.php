<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PlanSeeder::class,
            AdminUserSeeder::class,
        ]);

        Plan::all()->each(function ($plan) {
            Member::factory()
                ->count(30)
                ->for($plan)
                ->hasPayments(1)
                ->create();
        });
    }
}
