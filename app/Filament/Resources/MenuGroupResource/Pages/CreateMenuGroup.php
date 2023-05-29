<?php

namespace App\Filament\Resources\MenuGroupResource\Pages;

use App\Filament\Resources\MenuGroupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMenuGroup extends CreateRecord
{
    protected static string $resource = MenuGroupResource::class;

    public function getTitle(): string
    {
        return 'Создать группу меню';
    }
}
