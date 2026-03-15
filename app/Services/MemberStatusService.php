<?php

namespace App\Services;

use App\Models\Member;
use Carbon\Carbon;

class MemberStatusService
{
    public function updateStatuses(): void
    {
        $today = Carbon::today();

        Member::with('payments')->chunk(100, function ($members) use ($today) {
            foreach ($members as $member) {

                $referenceMonth = $today->format('Y-m');

                $hasPayment = $member->payments
                    ->where('reference_month', $referenceMonth)
                    ->exists();

                $dueDate = Carbon::create(
                    $today->year,
                    $today->month,
                    $member->due_day
                );

                $status = (!$hasPayment && $today->gt($dueDate))
                    ? 'overdue'
                    : 'active';

                if ($member->status !== $status) {
                    $member->update(['status' => $status]);
                }
            }
        });
    }
}
