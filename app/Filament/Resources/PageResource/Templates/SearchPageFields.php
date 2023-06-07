<?php

namespace App\Filament\Resources\PageResource\Templates;

use App\Services\CustomFieldsGetter;
use App\Services\CustomFieldsSaver;
use App\Services\CustomVar;
use App\Services\PageCustomFields;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class SearchPageFields extends PageCustomFields
{
    public function getSchema(): array
    {
        return [
            Section::make('Страница подбора')->collapsed()->schema([
                TextInput::make('vars.search.title')
                    ->label('Заголовок')
                    ->required(),

                TextInput::make('vars.search.why_us_title')
                    ->label('Заголовок почему мы')
                    ->nullable(),

                TinyEditor::make('vars.search.body')
                    ->label('Описание')
                    ->nullable(),

                TextInput::make('vars.search.video_link')
                    ->label('Ссылка на видео')
                    ->nullable(),

                TextInput::make('vars.search.steps_title')
                    ->label('Заголовок процесса')
                    ->nullable(),

                TextInput::make('vars.search.steps_body')
                    ->label('Подзаголовок процесса')
                    ->nullable(),

                TextInput::make('vars.search.map_title')
                    ->label('Заголовок карты')
                    ->nullable(),

                TextInput::make('vars.search.map_body')
                    ->label('Тело карты')
                    ->nullable(),

                TextInput::make('vars.search.cta_title')
                    ->label('Призыв к действию')
                    ->nullable(),

                FileUpload::make('vars.search.image')
                    ->label('Картинка')
                    ->image()
                    ->directory('form-tmp')
                    ->nullable(),
            ]),


            Section::make('Блок о выгодах')->collapsed()->schema([
                Repeater::make('vars.benefits')
                    ->createItemButtonLabel('Добавить выгоду')
                    ->disableLabel()
                    ->schema([
                        TextInput::make('title')->label('Заголовок')->required(),
                        TextInput::make('body')->label('Текст')->required(),
                    ])
            ]),


            Section::make('Шаги процесса')->collapsed()->schema([
                Repeater::make('vars.steps')
                    ->label('Шаги')
                    ->createItemButtonLabel('Создать шаг')
                    ->minItems(function ($state) {
                        if (count($state) == 0) {
                            return 0;
                        }
                        return 3;
                    })
                    ->maxItems(3)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->label('Заголовок'),

                        TextInput::make('body')
                            ->required()
                            ->label('Описание'),
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

        $getter->setImageFields('search', ['image' => 'search_main_image']);

        $getter->setRepeaterFields('benefits', new CustomVar(['title', 'body']));
        $getter->setRepeaterFields('steps', new CustomVar(['title', 'body']));

        return $getter->getFields();
    }

    public function saveFields(array $vars): void
    {
        $saver = new CustomFieldsSaver($vars);

        $saver->setPageVarFields(
            'search',
            new CustomVar(
                [
                    'title',
                    'body',
                    'video_link',
                    'steps_title',
                    'steps_body',
                    'map_title',
                    'map_body',
                    'cta_title',
                    'why_us_title'
                ], ['image' => 'search_main_image']
            )
        );

        $saver->setRepeatVarsFields('steps', new CustomVar(['title', 'body']));
        $saver->setRepeatVarsFields('benefits', new CustomVar(['title', 'body']));
        $saver->save($this->page);
    }
}
