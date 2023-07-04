<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\UserFile;
use App\Models\UserFileType;
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
        $typesWithFiles = null;

        if ($this->relationLoaded('files')) {
            $typesWithFiles = UserFileType::all()->map(function (UserFileType $type) {
                /** @var ?UserFile $file */
                $file = $this->files->firstWhere('type_id', $type->id);

                return [
                    'type' => $type,
                    'files' => $file !== null ? UserFileResource::make($file) : [],
                ];
            });
        }
        /** @var $this User */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'inn' => $this->inn,
            'org_name' => $this->org_name,
            'org_type' => $this->org_type,
            'geo' => GeoResource::make($this->geo),
            'phone' => $this->phone,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'files' => $this->whenNotNull($typesWithFiles),
        ];
    }
}
