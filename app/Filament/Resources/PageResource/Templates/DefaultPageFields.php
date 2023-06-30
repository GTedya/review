<?php

namespace App\Filament\Resources\PageResource\Templates;

use App\Models\PageVar;
use App\Models\RepeatVar;
use App\Services\CustomFieldsGetter;
use App\Services\CustomFieldsSaver;
use App\Services\CustomVar;
use App\Services\PageCustomFields;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class DefaultPageFields extends PageCustomFields
{
    public function getSchema(): array
    {
        return [
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

        $getter->setRepeaterFields('files', new CustomVar(['text'], ['file' => 'default_file']));

        return $getter->getFields();
    }

    public function saveFields(array $vars): void
    {
        $saver = new CustomFieldsSaver($vars);

        $saver->setPageVarFields(
            'default',
            new CustomVar(['content'], ['image' => 'default_image'])
        );

        $saver->setRepeatVarsFields('files', new CustomVar(['text'], ['file' => 'default_file']));
        $saver->save($this->page);
    }


    public function getPageVars(): array
    {
        /** @var ?PageVar $pageVar */
        $pageVar = $this->page->pageVar;
        if ($pageVar === null) {
            return [];
        }


        $repeatGroups = $this->page->pageVar->repeatVars->groupBy('name');

        $files = ($repeatGroups['files'] ?? null)?->map(function (RepeatVar $repeatVar) {
            $vars = $repeatVar->vars;
            return array_merge($vars, [
                'file' => $repeatVar->getFirstMediaUrl('default_file'),
            ]);
        });

        return [
            ...$pageVar->vars,
            'image' => $pageVar->getFirstMediaUrl('default_image'),
            'files' => $files,
        ];
    }
}
