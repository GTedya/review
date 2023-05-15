<?php

namespace App\Filament\Resources\RentResource\Pages;

use App\Filament\Resources\RentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRent extends EditRecord
{
    protected static string $resource = RentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Редактирование объявления аренды';
    }
}
