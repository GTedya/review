<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderManagersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $baseArray = OrderResource::make($this->resource)->toArray($request);
        return [
            ...$baseArray,
            'admin_comment' => $this->admin_comment,
        ];
    }
}
