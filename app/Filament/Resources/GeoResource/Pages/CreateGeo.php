<?php

namespace App\Filament\Resources\GeoResource\Pages;

use App\Filament\Resources\GeoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGeo extends CreateRecord
{
    protected static string $resource = GeoResource::class;

    public function getTitle(): string
    {
        return 'Создать ГЕО';
    }
}
