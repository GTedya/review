<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class OrderManagersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Order|self $this */

        $already_taken = $this->resource->managers->contains('id', Auth::id());

        $baseArray = OrderResource::make($this->resource)->toArray($request);
        return [
            ...$baseArray,
            'admin_comment' => $this->admin_comment,
            'already_taken' => $already_taken,
        ];
    }
}
