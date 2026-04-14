<?php

namespace App\Services;

use App\Http\Resources\PaymentResource;
use App\Models\Member;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentService
{

    public function list(array $filters = [], int $perPage = 5)
    {
        $query = Payment::with('member');

        if (!empty($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }

        if (!empty($filters['reference_month'])) {
            $query->where('reference_month', $filters['reference_month']);
        }

        if (!empty($filters['sort_by'])) {
            $direction = $filters['direction'] ?? 'asc';
            $query->orderBy($filters['sort_by'], $direction);
        } else {
            $query->latest();
        }

        return $query->paginate($perPage);
    }

    public function store(Request $request): Payment
    {
        $data = $request->validate([
            'member_id' => 'required|exists:members,id',
            'paid_at' => 'nullable|date',
            'receipt_path' => 'nullable|string',
        ]);

        $member = Member::with(['plan', 'payments'])->findOrFail($data['member_id']);

        if (!$member->plan) {
            abort(422, 'O membro não possui um plano ativo.');
        }

        $lastPayment = $member->payments()
            ->orderByDesc('reference_month')
            ->first();

        $nextMonth = $lastPayment
            ? Carbon::createFromFormat('Y-m', $lastPayment->reference_month)->addMonth()->format('Y-m')
            : now()->format('Y-m');

        $requestedMonth = isset($data['paid_at'])
            ? Carbon::parse($data['paid_at'])->format('Y-m')
            : $nextMonth;

        if ($requestedMonth !== $nextMonth) {
            throw ValidationException::withMessages([
                'payment' => ["Pagamento inválido. O próximo mês permitido é: {$nextMonth}."]
            ]);
        }

        if ($member->payments()->where('reference_month', $nextMonth)->exists()) {
            throw ValidationException::withMessages([
                'payment' => ['Pagamento para este mês já foi registrado.']
            ]);
        }

        $paidAt = isset($data['paid_at'])
            ? Carbon::parse($data['paid_at'])->format('Y-m-d')
            : now()->format('Y-m-d');

        $payment = Payment::create([
            'member_id' => $member->id,
            'amount' => $member->plan->price,
            'reference_month' => $nextMonth,
            'paid_at' => $paidAt,
            'receipt_path' => $data['receipt_path'] ?? null
        ]);

        $member->update([
            'status' => 'active'
        ]);

        return $payment->load('member');
    }

    public function show(Payment $payment): Payment
    {
        return $payment->load('member');
    }


    public function update(Request $request, Payment $payment): Payment
    {
        $data = $request->validate([
            'member_id' => 'sometimes|exists:members,id',
            'amount' => 'sometimes|numeric|min:0',
            'reference_month' => 'sometimes|date_format:Y-m',
            'paid_at' => 'nullable|date',
            'receipt_path' => 'nullable|string',
        ]);

        $payment->update($data);

        return $payment;
    }

    public function delete(Payment $payment): void
    {
        $member = $payment->member;
        $payment->delete();
        
        // Update member status after deletion
        $this->updateMemberStatus($member);
    }

    private function updateMemberStatus(Member $member): void
    {
        $today = now();
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


    public function getPaidPaymentsByMember(int $memberId, int $perPage = 5)
    {
        $query = Payment::where('member_id', $memberId)
            ->whereNotNull('paid_at')
            ->orderByDesc('paid_at')
            ->with('member');

        return PaymentResource::collection($query->paginate($perPage));
    }
}
