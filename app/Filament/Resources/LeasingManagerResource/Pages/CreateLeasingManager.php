<?php

namespace App\Filament\Resources\LeasingManagerResource\Pages;

use App\Filament\Resources\LeasingManagerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeasingManager extends CreateRecord
{
    protected static string $resource = LeasingManagerResource::class;

    public function getTitle(): string
    {
        return 'Создать менеджера';
    }
}
