<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected function getActions(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return 'Настройки';
    }

    public function mount($record = 1): void
    {
        $this->record = $this->resolveRecord(1);

        $this->authorizeAccess();

        $this->fillForm();
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament::resources/pages/edit-record.form.actions.cancel.label'))
            ->color('secondary')
            ->url('/admin');
    }
}
