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
        
        if (isset($data['is_active']) && !$data['is_active']) {
            // Check if plan has active members
            if ($plan->members()->where('status', 'active')->exists()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'is_active' => 'Não é possível desativar plano com membros ativos.'
                ]);
            }
        }
        
        $plan->update($data);
        return $plan->fresh();
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): array
    {
        $plan = Plan::findOrFail($id);
        
        if ($plan->members()->exists()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'plan' => 'Não é possível deletar plano com membros associados.'
            ]);
        }
        
        $plan->delete();
        
        return [
            'message' => 'Plano deletado com sucesso'
        ];
    }
}
