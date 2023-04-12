<?php

namespace App\Filament\Resources\UserFileTypesResource\Pages;

use App\Filament\Resources\UserFileTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserFileType extends EditRecord
{
    protected static string $resource = UserFileTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Редактирование типов файлов';
    }
}
