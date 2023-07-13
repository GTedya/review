<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeoResource extends JsonResource
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
            'region_code' => $this->region_code,
            'children' => GeoResource::collection($this->whenLoaded('childrenDeep')),
            'parent' => GeoResource::collection($this->whenLoaded('parentDeep')),
        ];
    }
}
