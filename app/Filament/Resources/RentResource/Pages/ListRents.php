<?php

namespace App\Filament\Resources\RentResource\Pages;

use App\Filament\Resources\RentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRents extends ListRecords
{
    protected static string $resource = RentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
