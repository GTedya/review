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
            'user' => AuthorResource::make($this->user),
            'name' => $this->name,
            'user_id' => $this->user_id,
            'email' => $this->email,
            'phone' => $this->phone,
            'end_date' => $this->end_date,
            'geo' => GeoResource::make($this->geo),
            'history' => OrderHistoryResource::collection($this->whenLoaded('orderHistory')),
            'inn' => $this->inn,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'leasing' => OrderLeasingResource::make($this->whenLoaded('leasing')),
            'leasing_vehicles' => OrderLeasingVehicleResource::collection($this->whenLoaded('leasingVehicles')),
            'dealer_vehicles' => OrderDealerVehicleResource::collection($this->whenLoaded('dealerVehicles')),
        ];
    }
}
