<?php

namespace App\Http\Resources;

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

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'files' => $this->whenNotNull($typesWithFiles),
        ];
    }
}
