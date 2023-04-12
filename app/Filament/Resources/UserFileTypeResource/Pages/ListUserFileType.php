<?php

namespace App\Filament\Resources\UserFileTypeResource\Pages;

use App\Filament\Resources\UserFileTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserFileType extends ListRecords
{
    protected static string $resource = UserFileTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
