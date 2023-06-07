<?php

namespace App\Filament\Resources\PageResource\Templates;

use App\Services\CustomFieldsGetter;
use App\Services\CustomFieldsSaver;
use App\Services\CustomVar;
use App\Services\PageCustomFields;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class DefaultPageFields extends PageCustomFields
{
    public function getSchema(): array
    {
        return [
            Fieldset::make('fields')->label('Переменные')->columns(1)->schema([

                Grid::make()->schema([
                    Section::make('Шаблон страницы по умолчанию')->collapsed()->schema([
                        TinyEditor::make('vars.default.content')->label('Контент'),

                        Fieldset::make('Изображение')->columns(1)->schema([
                            FileUpload::make('vars.default.image')
                                ->image()
                                ->enableOpen()
                                ->disableLabel()
                                ->label('Изображение')
                                ->directory('form-tmp')
                                ->panelLayout('integrated'),
                        ]),

                        Repeater::make('vars.files')->label('Файлы')
                            ->createItemButtonLabel('Добавить')
                            ->dehydrated(false)
                            ->schema([
                                TextInput::make('text')->label('Текст файла')->required(),

                                FileUpload::make('file')
                                    ->required()
                                    ->enableOpen()
                                    ->label('Файл')
                                    ->directory('form-tmp')
                            ]),
                    ]),
                ])
            ]),
        ];
    }

    public function getFields(): ?array
    {
        $pageVar = $this->page->fresh()->pageVar;

        if ($pageVar === null) {
            return null;
        }

        $getter = new CustomFieldsGetter($pageVar);

        $getter->setImageFields('default', ['image' => 'default_image']);

        $getter->setRepeaterFields('files', new CustomVar(['text'], ['file' => 'default_files']));

        return $getter->getFields();
    }

    public function saveFields(array $vars): void
    {
        $saver = new CustomFieldsSaver($vars);

        $saver->setPageVarFields(
            'default',
            new CustomVar(['content'], ['image' => 'default_image'])
        );

        $saver->setRepeatVarsFields('files', new CustomVar(['text'], ['file' => 'default_files']));
        $saver->save($this->page);
    }
}
