<?php

namespace App\Filament\Resources\GeoResource\Pages;

use App\Filament\Resources\GeoResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGeo extends EditRecord
{
    protected static string $resource = GeoResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Редактирование ГЕО';
    }
}
