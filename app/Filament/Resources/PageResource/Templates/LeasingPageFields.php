<?php

namespace App\Filament\Resources\PageResource\Templates;

use App\Http\Resources\PartnerResource;
use App\Models\Page;
use App\Models\PageVar;
use App\Models\Partner;
use App\Models\RepeatVar;
use App\Repositories\PartnerRepo;
use App\Services\CustomFieldsGetter;
use App\Services\CustomFieldsSaver;
use App\Services\CustomVar;
use App\Services\PageCustomFields;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class LeasingPageFields extends PageCustomFields
{
    public function __construct(
        private PartnerRepo $partnerRepo,
        Page $page,
    ) {
        parent::__construct($page);
    }

    public function getSchema(): array
    {
        return [
            Section::make('Страница одиничного лизинга')->collapsed()->schema([
                TextInput::make('vars.leasing.title')
                    ->label('Заголовок')
                    ->required(),

                Textarea::make('vars.leasing.body')
                    ->label('Подзаголовок')
                    ->required(),

                FileUpload::make('vars.leasing.image')
                    ->label('Заглавная картинка')
                    ->image()
                    ->directory('form-tmp')
                    ->nullable(),

                TextInput::make('vars.leasing.video_link')
                    ->label('Ссылка на видео в заголовке')
                    ->nullable(),

                TextInput::make('vars.leasing.benefits_title')
                    ->label('Заголовок блока выгоды')
                    ->nullable(),

                TextInput::make('vars.leasing.steps_title')
                    ->label('Заголовок процесса')
                    ->nullable(),

                TextInput::make('vars.leasing.steps_body')
                    ->label('Подзаголовок процесса')
                    ->nullable(),

                TextInput::make('vars.leasing.cta_title')
                    ->label('Призыв к действию')
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

            Section::make('Блок о выгодах')->collapsed()->schema([
                Repeater::make('vars.benefits')
                    ->createItemButtonLabel('Добавить выгоду')
                    ->disableLabel()
                    ->schema([
                        TextInput::make('title')->label('Заголовок')->required(),
                        TextInput::make('body')->label('Текст')->required(),
                    ])
            ]),

            Section::make('Блок описания')->collapsed()->schema([
                TextInput::make('vars.description.title')->label('Заголовок')->required(),
                TinyEditor::make('vars.description.body')->label('Текст')->required(),

                FileUpload::make('vars.description.image')
                    ->label('Картинка')
                    ->image()
                    ->directory('form-tmp')
                    ->nullable(),

                TextInput::make('vars.description.video_link')
                    ->label('Ссылка на видео')
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
        ];
    }

    public function getFields(): ?array
    {
        $pageVar = $this->page->fresh()->pageVar;

        if ($pageVar === null) {
            return null;
        }

        $getter = new CustomFieldsGetter($pageVar);

        $getter->setImageFields('leasing', ['image' => 'leasing_main_image']);
        $getter->setImageFields('description', ['image' => 'leasing_description_image']);

        $getter->setRepeaterFields('benefits', new CustomVar(['title', 'body']));
        $getter->setRepeaterFields('steps', new CustomVar(['title', 'body']));

        return $getter->getFields();
    }

    public function saveFields(array $vars): void
    {
        $saver = new CustomFieldsSaver($vars);

        $saver->setPageVarFields('partners', new CustomVar(['ids']));
        $saver->setPageVarFields(
            'leasing',
            new CustomVar(
                ['title', 'body', 'video_link', 'benefits_title', 'steps_title', 'steps_body', 'cta_title'],
                ['image' => 'leasing_main_image'],
            ),
        );
        $saver->setPageVarFields(
            'description',
            new CustomVar(
                ['title', 'body', 'video_link'],
                ['image' => 'leasing_description_image'],
            ),
        );

        $saver->setRepeatVarsFields('steps', new CustomVar(['title', 'body']));
        $saver->setRepeatVarsFields('benefits', new CustomVar(['title', 'body']));

        $saver->save($this->page);
    }

    public function getPageVars(): array
    {
        /** @var ?PageVar $pageVar */
        $pageVar = $this->page->pageVar;
        if ($pageVar === null) {
            return [];
        }


        $partners = $this->partnerRepo->getByIds(...($pageVar->vars['partners'] ?? []));
        $partners = PartnerResource::collection($partners);

        $repeatGroups = $this->page->pageVar->repeatVars->groupBy('name');

        $steps = ($repeatGroups['steps'] ?? null)?->map(function (RepeatVar $repeatVar) {
            return $repeatVar->vars;
        });
        $benefits = ($repeatGroups['benefits'] ?? null)?->map(function (RepeatVar $repeatVar) {
            return $repeatVar->vars;
        });

        return [
            ...array_merge($pageVar->vars, [
                'partners' => $partners,
            ]),
            'leasing_description_image' => $pageVar->getFirstMediaUrl('leasing_description_image'),
            'leasing_main_image' => $pageVar->getFirstMediaUrl('leasing_main_image'),
            'steps' => $steps,
            'benefits' => $benefits,
        ];
    }
}
