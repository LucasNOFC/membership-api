<?php

namespace App\Services;

use App\Http\Resources\PlanResource;
use App\Models\Plan;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Request;

class PlanService
{
    public function index()
    {
        return PlanResource::collection(
            Plan::where('is_active', true)->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        return Plan::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        return PlanResource::collection(
            $plan->load('members')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
