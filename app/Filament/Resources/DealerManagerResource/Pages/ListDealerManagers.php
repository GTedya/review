<?php

namespace App\Filament\Resources\DealerManagerResource\Pages;

use App\Filament\Resources\DealerManagerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDealerManagers extends ListRecords
{
    protected static string $resource = DealerManagerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
