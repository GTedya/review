<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var User|self $this  */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'company' => CompanyResource::make($this->company),
        ];
    }
}
