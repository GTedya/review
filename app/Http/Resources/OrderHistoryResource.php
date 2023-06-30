<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    const TRANSLATE = [
        'name' => 'ФИО',
        'phone' => 'Номер телефона',
        'email' => 'Email',
        'admin_comment' => 'Комментарий',
        'geo_id' => 'Область',
        'advance' => 'Аванс',
        'current_lessors' => 'Текущие лизингодатели',
        'months' => 'Срок лизинга',
        'leasing' => 'Лизинг',
        'leasing_vehicles' => 'ТС лизинга',
        'dealer_vehicles' => 'ТС дилера',
    ];

    public function toArray(Request $request): array
    {
        return [
            'edited' => $this->edited,
            'created_at' => $this->created_at,
            'translated' => $this->translate($this->edited),
        ];
    }

    public function translate($data)
    {
        $changes = [];
        foreach ($data as $word) {
            $changes [$word] = self::TRANSLATE[$word];
        }
        return $changes;
    }
}