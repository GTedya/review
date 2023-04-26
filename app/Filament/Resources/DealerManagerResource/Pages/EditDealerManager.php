<?php

namespace App\Filament\Resources\DealerManagerResource\Pages;

use App\Filament\Resources\DealerManagerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDealerManager extends EditRecord
{
    protected static string $resource = DealerManagerResource::class;

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
