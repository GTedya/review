<?php

namespace App\Filament\Resources\PageResource\Templates;

use App\Models\Partner;
use App\Services\CustomFieldsGetter;
use App\Services\CustomFieldsSaver;
use App\Services\CustomVar;
use App\Services\PageCustomFields;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class AboutPageFields extends PageCustomFields
{
    public function getSchema(): array
    {
        return [
            Section::make('О нас')->collapsed()->schema([
                TextInput::make('vars.about.title')
                    ->label('Заголовок')
                    ->required(),

                TinyEditor::make('vars.about.body')
                    ->label('Описание')
                    ->nullable(),

                TextInput::make('vars.about.video_link')
                    ->label('Ссылка на видео')
                    ->nullable(),

                TextInput::make('vars.about.steps_title')
                    ->label('Заголовок процесса')
                    ->nullable(),

                TextInput::make('vars.about.steps_body')
                    ->label('Подзаголовок процесса')
                    ->nullable(),

                FileUpload::make('vars.about.image')
                    ->label('Картинка')
                    ->image()
                    ->directory('form-tmp')
                    ->nullable(),
            ]),

            Section::make('Партнеры')->collapsed()->schema([
                Select::make('vars.partners.ids')
                    ->label('Партнеры')
                    ->nullable()
                    ->multiple()
                    ->disableLabel()
                    ->options(Partner::all()->pluck('name', 'id')),
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

        $getter->setImageFields('about', ['image' => 'about_main_image']);

        $getter->setRepeaterFields('steps', new CustomVar(['title', 'body']));

        return $getter->getFields();
    }

    public function saveFields(array $vars): void
    {
        $saver = new CustomFieldsSaver($vars);

        $saver->setPageVarFields('partners', new CustomVar(['ids']));
        $saver->setPageVarFields(
            'about',
            new CustomVar(['title', 'body', 'video_link', 'steps_title', 'steps_body'], ['image' => 'about_main_image'])
        );

        $saver->setRepeatVarsFields('steps', new CustomVar(['title', 'body']));
        $saver->save($this->page);
    }
}
