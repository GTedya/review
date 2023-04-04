<?php

namespace App\Filament\Resources\GeoResource\Pages;

use App\Filament\Resources\GeoResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeos extends ListRecords
{
    protected static string $resource = GeoResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
