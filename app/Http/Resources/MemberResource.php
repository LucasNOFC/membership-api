<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'due_day' => $this->due_day,

            'plan' => new PlanResource($this->whenLoaded('plan')),
            'payments_count' => $this->payments()->count(),

            'created_at' => $this->created_at,
        ];
    }
}
