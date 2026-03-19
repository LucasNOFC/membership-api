<?php

namespace App\Services;

use App\Http\Resources\PlanResource;
use App\Models\Plan;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

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
    public function store(array $data): Plan
    {
       return Plan::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function find(int $id): Plan
    {
        return Plan::findOrFail($id);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(array $data, int $id): Plan
    {
        $plan = Plan::findOrFail($id);
        $plan->update($data);
        return $plan->fresh();
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
