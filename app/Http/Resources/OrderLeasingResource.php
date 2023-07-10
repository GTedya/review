<?php

namespace App\Http\Resources;

use App\Models\OrderLeasing;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderLeasingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**@var OrderLeasing $this */
        return [
            'id' => $this->id,
            'advance' => $this->advance,
            'sum' => $this->sum,
            'months' => $this->months,
            'current_lessors' => $this->current_lessors,
        ];
    }
}
