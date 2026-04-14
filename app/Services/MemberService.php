<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
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
        return DB::transaction(function () use ($data) {
            $plan = \App\Models\Plan::findOrFail($data['plan_id']);
            if (!$plan->is_active) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'plan_id' => 'O plano selecionado não está ativo.'
                ]);
            }

            $member = Member::create($data);

            $member->load('plan');

            Payment::create([
                'member_id'       => $member->id,
                'amount'          => $member->plan->price,
                'reference_month' => now()->format('Y-m'),
                'paid_at'         => now(),
                'status'          => 'paid',
            ]);

            return $member;
        });
    }

    public function update(array $data, int $id): Member
    {
        $member = Member::findOrFail($id);
        
        if (isset($data['plan_id'])) {
            $plan = \App\Models\Plan::findOrFail($data['plan_id']);
            if (!$plan->is_active) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'plan_id' => 'O plano selecionado não está ativo.'
                ]);
            }
        }
        
        $member->update($data);
        return $member->fresh();
    }

    public function delete(int $id): array
    {
        $member = Member::findOrFail($id);

        // Check for unpaid payments
        $unpaidPayments = $member->payments()->whereNull('paid_at')->count();
        if ($unpaidPayments > 0) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'member' => 'Não é possível deletar membro com pagamentos pendentes.'
            ]);
        }

        $member->delete();

        return [
            'message' => 'Membro deletado com sucesso',
            'member' => $member
        ];
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
