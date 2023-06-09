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
use Filament\Forms\Components\Toggle;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class MainPageFields extends PageCustomFields
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
            Section::make('Заглавный слайдер')->collapsed()->schema([
                Repeater::make('vars.title_slider')
                    ->label('Слайды')
                    ->createItemButtonLabel('Добавить слайд')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->label('Заголовок'),

                        Textarea::make('text')
                            ->required()
                            ->label('Текст'),

                        Toggle::make('button_open_modal')->label('Кнопка всплывающего окна'),

                        TextInput::make('link')->nullable()->label('Ссылка'),

                        FileUpload::make('image')
                            ->label('Изображение')
                            ->image()
                            ->directory('form-tmp')
                            ->required(),
                    ]),
            ]),

            Section::make('Инфо блоки')->collapsed()->schema([
                Repeater::make('vars.info_tiles')
                    ->label('Блоки')
                    ->createItemButtonLabel('Создать инфо блок')
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

                        FileUpload::make('logo')
                            ->label('Лого')
                            ->image()
                            ->directory('form-tmp')
                            ->required(),
                    ]),
            ]),

            Section::make('Почему мы')->collapsed()->schema([
                TextInput::make('vars.why_us.title')
                    ->label('Заголовок')
                    ->required(),

                TinyEditor::make('vars.why_us.body')
                    ->label('Описание')
                    ->nullable(),

                TextInput::make('vars.why_us.video_link')
                    ->label('Ссылка на видео')
                    ->nullable(),

                FileUpload::make('vars.why_us.preview')
                    ->label('Превью')
                    ->image()
                    ->directory('form-tmp')
                    ->required(),
            ]),

            Section::make('Партнеры')->collapsed()->schema([
                Select::make('vars.partners.ids')
                    ->label('Партнеры')
                    ->nullable()
                    ->multiple()
                    ->disableLabel()
                    ->options(Partner::all()->pluck('name', 'id')),
            ]),

            Section::make('Блок карты')->collapsed()->schema([
                TextInput::make('vars.map.title')
                    ->label('Заголовок')
                    ->required(),

                Textarea::make('vars.map.body')
                    ->label('Тект')
                    ->required(),
            ]),

            Section::make('Блок о выгодах')->collapsed()->schema([
                Repeater::make('vars.benefits')
                    ->createItemButtonLabel('Добавить выгоду')
                    ->disableLabel()
                    ->schema([
                        TextInput::make('title')->label('Заголовок')->required(),
                        TextInput::make('text')->label('Текст')->required(),
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

        $getter->setImageFields('why_us', ['preview' => 'main_why_us_preview']);

        $getter->setRepeaterFields(
            'title_slider',
            new CustomVar(['title', 'text', 'button_open_modal', 'link'], ['image' => 'main_slider_image'])
        );
        $getter->setRepeaterFields('benefits', new CustomVar(['title', 'text']));
        $getter->setRepeaterFields('info_tiles', new CustomVar(['title', 'body'], ['logo' => 'main_info_tiles_logo']));

        return $getter->getFields();
    }

    public function saveFields(array $vars): void
    {
        $saver = new CustomFieldsSaver($vars);

        $saver->setPageVarFields('map', new CustomVar(['title', 'body']));
        $saver->setPageVarFields('partners', new CustomVar(['ids']));
        $saver->setPageVarFields(
            'why_us',
            new CustomVar(['title', 'body', 'video_link'], ['preview' => 'main_why_us_preview'])
        );

        $saver->setRepeatVarsFields(
            'title_slider',
            new CustomVar(['title', 'text', 'button_open_modal', 'link'], ['image' => 'main_slider_image'])
        );
        $saver->setRepeatVarsFields('info_tiles', new CustomVar(['title', 'body'], ['logo' => 'main_info_tiles_logo']));
        $saver->setRepeatVarsFields('benefits', new CustomVar(['title', 'text']));

        $saver->save($this->page);
    }

    public function getPageVars(): array
    {
        /** @var PageVar $pageVar */
        $pageVar = $this->page->pageVar;

        $partners = $this->partnerRepo->getByIds(...$pageVar['vars']['partners']);
        $partners = PartnerResource::collection($partners);

        $repeatGroups = $this->page->pageVar->repeatVars->groupBy('name');

        $titleSlider = $repeatGroups['title_slider']?->map(function (RepeatVar $repeatVar) {
            $vars = $repeatVar->vars;
            return array_merge($vars, [
                'image' => $repeatVar->getFirstMediaUrl('main_slider_image'),
            ]);
        });
        $infoTiles = $repeatGroups['info_tiles']?->map(function (RepeatVar $repeatVar) {
            $vars = $repeatVar->vars;
            return array_merge($vars, [
                'image' => $repeatVar->getFirstMediaUrl('main_info_tiles_logo'),
            ]);
        });

        return [
            ...array_merge($pageVar->vars, [
                'partners' => $partners,
            ]),
            'main_why_us_preview' => $pageVar->getFirstMediaUrl('main_why_us_preview'),
            'title_slider' => $titleSlider,
            'info_tiles' => $infoTiles,
        ];
    }
}
