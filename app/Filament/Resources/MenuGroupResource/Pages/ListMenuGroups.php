<?php

namespace App\Filament\Resources\MenuGroupResource\Pages;

use App\Filament\Resources\MenuGroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMenuGroups extends ListRecords
{
    protected static string $resource = MenuGroupResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
