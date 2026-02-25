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
        // User::factory(10)->create();

        Plan::factory()
            ->count(3)
            ->hasMembers(
                Member::factory()
                    ->count(10)
                    ->hasPayments(2)
            )
            ->create();
    }
}
