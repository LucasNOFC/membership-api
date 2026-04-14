<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use App\Services\MemberService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    protected $service;

    public function __construct(MemberService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'sort_by', 'direction']);

        return MemberResource::collection($this->service->list($filters));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:members,email',
            'phone' => 'required|string|max:20',
            'plan_id' => 'required|exists:plans,id',
            'due_day' => 'required|integer|min:1|max:31',
        ]);

        return new MemberResource($this->service->create($data));
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return new MemberResource(
            $this->service->find($member->id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('members', 'email')->ignore($id)],
            'phone' => 'required|string|max:20',
            'plan_id' => 'required|exists:plans,id',
            'due_day' => 'required|integer|min:1|max:31',
        ]);

        $member = $this->service->update($data, $id);

        return response()->json([
            'message' => 'Membro atualizado',
            'member' => $member
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        $result = $this->service->delete($id);

        return response()->json($result);
    }
}
