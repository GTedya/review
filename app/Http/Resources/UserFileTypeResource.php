<?php

namespace App\Http\Resources;

use App\Models\UserFileType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFileTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var self|UserFileType $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
