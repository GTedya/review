<?php

namespace App\Filament\Resources\UserFileTypesResource\Pages;

use App\Filament\Resources\UserFileTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserFileType extends CreateRecord
{
    protected static string $resource = UserFileTypeResource::class;

    public function getTitle(): string
    {
        return 'Создать тип файла';
    }
}
