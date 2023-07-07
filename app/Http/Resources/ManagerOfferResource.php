<?php

namespace App\Http\Resources;

use App\Models\ManagerOffer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagerOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**@var ManagerOffer|self $this */
        return [
            'manager' => ManagerResource::make($this->manager),
            'file' => $this->resource->getFirstMediaUrl('offer_file'),
            'created_at' => $this->created_at
        ];
    }
}
