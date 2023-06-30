<?php

namespace App\Http\Resources;

use App\Services\PageCustomFields;
use App\Utilities\Helpers;
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
        $breadcrumbs = Helpers::getBreadcrumbs($this);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'meta' => $this->meta,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at,
            'pageVars' => $this->getPageVars(),
            'breadcrumbs' => $breadcrumbs,
        ];
    }

    private function getPageVars(): array
    {
        return PageCustomFields::getInstance($this->resource)->getPageVars();
    }
}
