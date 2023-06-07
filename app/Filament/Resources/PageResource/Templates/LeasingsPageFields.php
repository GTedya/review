<?php

namespace App\Filament\Resources\PageResource\Templates;

use App\Models\Partner;
use App\Services\CustomFieldsGetter;
use App\Services\CustomFieldsSaver;
use App\Services\CustomVar;
use App\Services\PageCustomFields;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class LeasingsPageFields extends PageCustomFields
{
    public function getSchema(): array
    {
        return [
            Fieldset::make('fields')->label('Переменные')->columns(1)->schema([
                Grid::make()->schema([


                    Section::make('Страница лизинга')->collapsed()->schema([
                        TextInput::make('vars.leasings.title')
                            ->label('Заголовок')
                            ->required(),

                        TextInput::make('vars.leasings.benefits_title')
                            ->label('Заголовок блока выгоды')
                            ->nullable(),

                        TextInput::make('vars.leasings.steps_title')
                            ->label('Заголовок процесса')
                            ->nullable(),

                        TextInput::make('vars.leasings.steps_body')
                            ->label('Подзаголовок процесса')
                            ->nullable(),

                        TextInput::make('vars.leasings.cta_title')
                            ->label('Призыв к действию')
                            ->nullable(),

                        FileUpload::make('vars.leasings.image')
                            ->label('Картинка')
                            ->image()
                            ->directory('form-tmp')
                            ->nullable(),
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


                    Section::make('Партнеры')->collapsed()->schema([
                        Select::make('vars.partners.ids')
                            ->label('Партнеры')
                            ->nullable()
                            ->multiple()
                            ->disableLabel()
                            ->options(Partner::all()->pluck('name', 'id')),
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

        $getter->setImageFields('leasings', ['image' => 'leasings_main_image']);

        $getter->setRepeaterFields('benefits', new CustomVar(['title', 'body']));
        $getter->setRepeaterFields('steps', new CustomVar(['title', 'body']));

        return $getter->getFields();
    }

    public function saveFields(array $vars): void
    {
        $saver = new CustomFieldsSaver($vars);

        $saver->setPageVarFields('partners', new CustomVar(['ids']));
        $saver->setPageVarFields(
            'leasings',
            new CustomVar(['title', 'benefits_title', 'steps_title', 'steps_body', 'cta_title'],
                ['image' => 'leasings_main_image'])
        );

        $saver->setRepeatVarsFields('steps', new CustomVar(['title', 'body']));
        $saver->setRepeatVarsFields('benefits', new CustomVar(['title', 'body']));

        $saver->save($this->page);
    }
}
