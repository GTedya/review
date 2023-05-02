<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderLeasingResource extends JsonResource
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
            'advance' => $this->advance,
            'months' => $this->months,
            'current_lessors' => $this->current_lessors,
            'user_comment' => $this->user_comment,
            'vehicles' => OrderLeasingVehicleResource::collection($this->vehicles),
        ];
    }
}
