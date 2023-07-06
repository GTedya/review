<?php

namespace App\Http\Resources;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var $this Setting */
        $phone = array_map(function ($item) {
            return $item['number'];
        }, $this->phone);
        return [
            'id' => $this->id,
            'phone' => $phone,
            'email' => $this->email,
            'telegram' => $this->telegram,
            'vk' => $this->vk,
            'app_store' => $this->app_store,
            'google_play' => $this->google_play,
            'og_image' => $this->getFirstMediaUrl('og_image'),
            'contact_file' => $this->getFirstMediaUrl('contact_file')
        ];
    }
}
