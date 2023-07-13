<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Order|self $this */
        return [
            'id' => $this->id,
            'geo' => GeoResource::make($this->geo),
            'status' => $this->status,
            'created_at' => $this->created_at,
            'leasing' => OrderLeasingResource::make($this->whenLoaded('leasing')),
            'offer_count' => $this->offers()->count(),
        ];
    }
}
