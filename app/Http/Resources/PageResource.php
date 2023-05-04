<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'meta' => $this->meta,
            'created_at' => $this->created_at,
            'image' => $this->getFirstMediaUrl('image'),
            'files' => FileResource::collection($this->whenLoaded('files')),
        ];
    }
}