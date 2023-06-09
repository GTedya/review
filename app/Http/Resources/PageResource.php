<?php

namespace App\Http\Resources;

use App\Services\PageCustomFields;
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
            'meta' => $this->meta,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at,
            'pageVars' => $this->getPageVars(),
        ];
    }

    private function getPageVars(): array
    {
        return PageCustomFields::getInstance($this->resource)->getPageVars();
    }
}
