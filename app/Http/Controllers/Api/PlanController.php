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
        $this->middleware('auth:sanctum');
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
        $this->authorize('create', Plan::class);
        return $this->service->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        $this->authorize('view', $plan);

        return $this->service->show($plan);
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
