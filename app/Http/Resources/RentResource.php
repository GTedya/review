<?php

namespace App\Http\Resources;

use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class RentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Rent|self $this */
        return [
            'id' => $this->id,
            'user' => AuthorResource::make($this->user),
            'name' => $this->name,
            'title' => $this->title,
            'type' => $this->type,
            'email' => $this->email,
            'preview_description' => mb_substr($this->text, 0, 150),
            'text' => $this->text,
            'slug' => $this->slug,
            'active_until' => $this->active_until,
            'is_published' => $this->is_published,
            'title_image' => $this->getFirstMediaUrl('images'),
            'images' => $this->getMedia('images')->map(function (Media $image) {
                return $image->getUrl();
            }),
            'phone' => $this->phone,
            'with_nds' => $this->with_nds,
            'geo' => GeoResource::make($this->geo),
            'created_at' => $this->created_at,
            'rent_vehicle' => RentVehicleResource::collection($this->whenLoaded('rentVehicles')),
        ];
    }
}
