<?php

namespace App\Filament\Resources\UserFileTypesResource\Pages;

use App\Filament\Resources\UserFileTypesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserFileTypes extends EditRecord
{
    protected static string $resource = UserFileTypesResource::class;

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
