<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Events\OrderUpdate;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * @property Order $record
 */
class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;


    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Редактирование заказа';
    }

    protected function afterSave()
    {
        event(new OrderUpdate($this->record));
    }
}
