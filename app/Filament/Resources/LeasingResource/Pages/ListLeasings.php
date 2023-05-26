<?php

namespace App\Filament\Resources\LeasingResource\Pages;

use App\Filament\Resources\LeasingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeasings extends ListRecords
{
    protected static string $resource = LeasingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
