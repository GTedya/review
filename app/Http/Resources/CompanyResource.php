<?php

namespace App\Http\Resources;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var $this Company */
        return [
            'id' => $this->id,
            'inn' => $this->inn,
            'org_type' => $this->org_type,
            'org_name' => $this->org_name,
            'geo' => GeoResource::make($this->geo),
        ];
    }
}
