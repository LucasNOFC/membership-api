<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Support\Collection;

class MemberService
{
    public function list(array $filters = [], int $perPage = 10)
    {
        $query = Member::with('plan');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['sort_by'])) {
            $direction = $filters['direction'] ?? 'asc';
            $query->orderBy($filters['sort_by'], $direction);
        } else {
            $query->latest();
        }

        return $query->paginate($perPage);
    }

    public function create(array $data): Member
    {
        return Member::create($data);
    }

    public function update(Member $member, array $data): Member
    {
        $member->update($data);
        return $member;
    }

    public function delete(Member $member): void
    {
        $member->delete();
    }

    public function find(int $id): Member
    {
        return Member::with('plan', 'payments')->findOrFail($id);
    }

    public function updateStatuses(): void
    {
        $today = now()->startOfDay();

        Member::with('payments')->chunk(100, function ($members) use ($today) {
            foreach ($members as $member) {
                $referenceMonth = $today->format('Y-m');
                $hasPayment = $member->payments()
                    ->where('reference_month', $referenceMonth)
                    ->exists();

                $dueDate = now()->year($today->year)
                                ->month($today->month)
                                ->day($member->due_day);

                $member->update([
                    'status' => $hasPayment || $today->lte($dueDate) ? 'active' : 'overdue'
                ]);
            }
        });
    }
}