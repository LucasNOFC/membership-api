<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Services\PlanService;
use Illuminate\Http\Request;

class PlanController extends Controller
{

    protected $service;

    public function __construct(PlanService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
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

        return new PlanResource($this->service->store($data));
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        return new PlanResource(
            $this->service->find($plan->id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'duration_days' => 'sometimes|required|integer|min:1',
            'is_active' => 'sometimes|boolean',
            'description' => 'nullable|string',
        ]);

        $plan = $this->service->update($data, $id);

        return response()->json([
            'message' => 'Plano atualizado com sucesso',
            'data' => $plan
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
