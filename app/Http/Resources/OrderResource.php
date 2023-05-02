<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user_id' => $this->user_id,
            'email' => $this->email,
            'phone' => $this->phone,
            'end_date' => $this->end_date,
            'geo_id' => $this->geo_id,
            'inn' => $this->inn,
            'status_id' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'leasing' => OrderLeasingResource::make($this->whenLoaded('leasing')),
            'dealer' => OrderDealerResource::make($this->whenLoaded('dealer')),
        ];
    }
}
