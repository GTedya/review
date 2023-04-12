<?php

namespace App\Filament\Resources\UserFileTypesResource\Pages;

use App\Filament\Resources\UserFileTypesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserFileTypes extends ListRecords
{
    protected static string $resource = UserFileTypesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
