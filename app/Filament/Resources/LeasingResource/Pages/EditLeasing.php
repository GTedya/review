<?php

namespace App\Filament\Resources\LeasingResource\Pages;

use App\Filament\Resources\LeasingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeasing extends EditRecord
{
    protected static string $resource = LeasingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Редактирование вида лизинга';
    }
}
