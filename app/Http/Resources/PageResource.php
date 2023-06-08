<?php

namespace App\Http\Resources;

use App\Models\PageVar;
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
            'parent_id' => $this->parent_id,
            'vars' => PageVarResource::make($this->pageVar),
            'meta' => $this->meta,
            'created_at' => $this->created_at,
        ];
    }
}
