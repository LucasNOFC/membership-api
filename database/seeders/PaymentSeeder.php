<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = Member::all();

        $count = min(50, $members->count());

        for ($i = 0; $i < $count; $i++) {
            $member = $members[$i];

            $planPrice = $member->plan->price;

            $monthStart = now()->startOfMonth();
            $monthEnd = now()->endOfMonth();
            $paidAt = fake()->dateTimeBetween($monthStart, $monthEnd);

            Payment::create([
                'member_id' => $member->id,
                'amount' => $planPrice,
                'reference_month' => $paidAt->format('Y-m'),
                'paid_at' => $paidAt,
                'receipt_path' => null,
            ]);
        }
    }
}