<?php

namespace App\Filament\Resources\MenuGroupResource\Pages;

use App\Filament\Resources\MenuGroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenuGroup extends EditRecord
{
    protected static string $resource = MenuGroupResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    public function getTitle(): string
    {
        return 'Редактирование группы меню';
    }

}
