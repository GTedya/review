<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderLeasingVehicleResource extends JsonResource
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
            'type' => VehicleTypeResource::make($this->type),
            'brand' => $this->brand,
            'model' => $this->model,
            'count' => $this->count,
            'state' => $this->state,
        ];
    }
}
