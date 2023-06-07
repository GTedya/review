<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use App\Services\PageCustomFields;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;
    private ?PageCustomFields $pageCustomFields = null;

    private const UNTOUCHABLE = [1, 2, 3, 4];

    protected function getActions(): array
    {
        return (in_array($this->record->id, self::UNTOUCHABLE)) ? array() : [Actions\DeleteAction::make(),];
    }

    private function initCustomFields(): void
    {
        $this->pageCustomFields ??= PageCustomFields::getInstance($this->record);
    }

    public function getTitle(): string
    {
        return 'Редактирование страницы';
    }

    protected function getFormSchema(): array
    {
        $this->initCustomFields();

        return array_merge(
            $this->getResourceForm()->getSchema(),
            $this->pageCustomFields?->getSchema() ?? []
        );
    }


    protected function afterValidate()
    {
        if ($this->pageCustomFields === null) {
            return;
        }

        /** @var ?array $vars */
        $vars = $this->data['vars'] ?? null;

        if ($vars === null) {
            return;
        }

        $this->pageCustomFields->saveFields($vars);
    }

    protected function afterFill()
    {
        $fields = $this->pageCustomFields?->getFields();
        if ($fields !== null) {
            $this->form->fill($fields + $this->data);
        }
    }

    protected function afterSave()
    {
        $this->afterFill();
    }
}
