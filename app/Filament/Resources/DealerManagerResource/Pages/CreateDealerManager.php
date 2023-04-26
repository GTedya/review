<?php

namespace App\Filament\Resources\DealerManagerResource\Pages;

use App\Filament\Resources\DealerManagerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDealerManager extends CreateRecord
{
    protected static string $resource = DealerManagerResource::class;

    public function getTitle(): string
    {
        return 'Создать менеджера';
    }
}
