<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Utilities\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var User|self $this */

        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email ?? $this->user->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'files' => $this->whenNotNull(Helpers::userFiles($this->resource, false)),
            'company' => CompanyResource::make($this->company),
        ];
    }
}
