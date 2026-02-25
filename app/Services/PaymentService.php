<?php

namespace App\Services;

use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentService
{

    public function index(array $filters = [])
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

        return PaymentResource::collection($query->paginate(10));
    }

    public function store(Request $request): Payment
    {
        $data = $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0',
            'reference_month' => 'required|date_format:Y-m',
            'paid_at' => 'nullable|date',
            'receipt_path' => 'nullable|string',
        ]);

        return Payment::create($data);
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
        $payment->delete();
    }
}