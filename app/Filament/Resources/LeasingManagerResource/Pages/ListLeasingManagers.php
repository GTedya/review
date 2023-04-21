<?php

namespace App\Filament\Resources\LeasingManagerResource\Pages;

use App\Filament\Resources\LeasingManagerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeasingManagers extends ListRecords
{
    protected static string $resource = LeasingManagerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
