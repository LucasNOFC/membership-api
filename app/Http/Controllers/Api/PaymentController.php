<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $service;

    public function __construct(PaymentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request, $member = null)
    {
        $filters = $request->only(['member_id', 'reference_month', 'sort_by', 'direction']);
        
        if ($member) {
            $filters['member_id'] = $member;
        }

        return $this->service->index($filters);
    }

    public function store(Request $request, $member)
    {
        $request->merge(['member_id' => $member]);
        
        $this->authorize('create', Payment::class);
        
        return $this->service->store($request);
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);
        return $this->service->show($payment);
    }

    public function update(Request $request, Payment $payment)
    {
        $this->authorize('update', $payment);
        return $this->service->update($request, $payment);
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);
        $this->service->delete($payment);
        return response()->noContent();
    }
}