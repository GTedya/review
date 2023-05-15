<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RentResource extends JsonResource
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
            'user' => UserResource::make($this->user),
            'name' => $this->name,
            'type' => $this->type,
            'email' => $this->email,
            'is_published' => $this->is_published,
            'phone' => $this->phone,
            'geo' => GeoResource::make($this->geo),
            'created_at' => $this->created_at,
            'rent_vehicle' => RentVehicleResource::collection($this->whenLoaded('rentVehicles')),
        ];
    }
}
