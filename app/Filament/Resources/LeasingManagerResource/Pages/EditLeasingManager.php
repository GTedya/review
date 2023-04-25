<?php

namespace App\Filament\Resources\LeasingManagerResource\Pages;

use App\Filament\Resources\LeasingManagerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeasingManager extends EditRecord
{
    protected static string $resource = LeasingManagerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Редактирование менеджера';
    }
}
