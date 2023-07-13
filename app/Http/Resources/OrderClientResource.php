<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Order|self $this */

        $baseArray = OrderResource::make($this->resource)->toArray($request);
        return [
            ...$baseArray,
            'user_comment' => $this->user_comment,
        ];
    }
}
