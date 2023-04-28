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
            'id' =>$this->id,
            'order_leasing_id'=>$this->order_leasing_id,
            'type_id'=>$this->type_id,
            'brand'=>$this->brand,
            'model'=>$this->model,
            'count'=>$this->count,
            'state'=>$this->state,
        ];
    }
}
