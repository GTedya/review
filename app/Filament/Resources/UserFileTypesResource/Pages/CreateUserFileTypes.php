<?php

namespace App\Filament\Resources\UserFileTypesResource\Pages;

use App\Filament\Resources\UserFileTypesResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserFileTypes extends CreateRecord
{
    protected static string $resource = UserFileTypesResource::class;

    public function getTitle(): string
    {
        return 'Создать тип файла';
    }
}
